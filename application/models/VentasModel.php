<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VentasModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Asegúrate de que la base de datos esté cargada
    }

// Método para crear la tabla temporal
public function crearTablaTemporal(
    $fecha, $fecha_inicio, $fecha_fin, $sedeClause, $diaClause, $turnoClause, $especieClause, $areaClause
) {
    $tabla_temporal = "TempVentasUnicas_" . $fecha;

    // Consulta para crear la tabla temporal
    $sql = "
        CREATE TEMPORARY TABLE $tabla_temporal AS
        SELECT DISTINCT 
    s.SaleId, 
    s.TenantId,
    s.PatientId,
    s.MascotaPatientId,
    ma.especie,
    s.RUC,
    s.Anulado,
    s.TipoDeComprobante,
    CASE 
        WHEN s.Anulado = 1 THEN 0
        WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalTotal 
        ELSE s.GlobalTotal 
    END AS TotalUnico,
            (SELECT GROUP_CONCAT(DISTINCT sd.ServicioId) 
             FROM sale_details sd WHERE sd.SaleId = s.SaleId) AS Servicios,
            (SELECT GROUP_CONCAT(DISTINCT sd.ProductoId) 
             FROM sale_details sd WHERE sd.SaleId = s.SaleId) AS Productos,
            (SELECT SUM(sd.Cantidad)
             FROM sale_details sd WHERE sd.SaleId = s.SaleId) AS CantidadTotal,
             (SELECT SUM(sd.TotalConIGV) 
             FROM sale_details sd WHERE sd.SaleId = s.SaleId) AS TotalConIGV,

             -- Cálculo del área
           COALESCE(
    (SELECT srv.Area 
     FROM sale_details sd 
     INNER JOIN servicios srv ON sd.ServicioId = srv.ServicioId 
     WHERE sd.SaleId = s.SaleId LIMIT 1),  -- Obtener solo el primer área de los servicios
    (SELECT p.Area 
     FROM sale_details sd 
     INNER JOIN productos p ON sd.ProductoId = p.ProductoId 
     WHERE sd.SaleId = s.SaleId LIMIT 1),  -- Obtener solo el primer área de los productos
    'OTROS'  -- Si no se encuentran áreas, se asigna 'OTROS'
) AS Area

        FROM 
            sales s
        INNER JOIN 
            sedes se ON s.TenantId = se.TenantId
        LEFT JOIN 
            mascotas2 ma ON s.MascotaPatientId = ma.patient_id
        INNER JOIN 
            sale_details sd ON s.SaleId = sd.SaleId
        LEFT JOIN 
            servicios srv ON sd.ServicioId = srv.ServicioId
        LEFT JOIN 
            productos p ON sd.ProductoId = p.ProductoId
        WHERE 
            s.FechaDelDocumento BETWEEN ? AND ?
            $sedeClause
            $diaClause
            $turnoClause
            $especieClause
            $areaClause
            
    ";
    // AND srv.Nombre not in ('CAFE CLASICO','CAFE CLASICO COLABORADORES','CAFE VARIOS','CAFE VARIOS COLABORADORES')
    // Ejecutar la creación de la tabla temporal
    return $this->db->query($sql, [$fecha_inicio, $fecha_fin]);
}


// Método para obtener ventas agrupadas por sede
public function obtenerVentasPorSedeAgrupadas($fecha) {
    $tabla_temporal = "TempVentasUnicas_" . $fecha;

    // Consulta para obtener cantidad de transacciones, clientes únicos y total de ventas agrupados por sede
    $sql = "
        SELECT 
            se.Nombre AS sede,
            COUNT(DISTINCT v.SaleId) AS TotalVentas,
            COUNT(DISTINCT v.PatientId) + COUNT(DISTINCT v.RUC) AS TotalClientesUnicos,
            SUM(v.TotalUnico) AS SumaVentas
        FROM 
            $tabla_temporal v
        INNER JOIN 
            sedes se ON v.TenantId = se.TenantId
        GROUP BY 
            se.TenantId
        ORDER BY 
            se.orden ASC;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);
    return $query->result_array();
}

// Método para obtener ventas agrupadas por áreas
public function obtenerVentasPorAreas($fecha) {
    $tabla_temporal = "TempVentasUnicas_" . $fecha;

    // Consulta para obtener las ventas agrupadas por área
    $sql = "
 SELECT 
            'Petmax' AS empresa,
            Area AS AREA,
            SUM(TotalUnico) AS TotalVentas
        FROM 
            $tabla_temporal
        GROUP BY 
            Area
        ORDER BY 
            TotalVentas DESC;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);
    return $query->result_array();
}

public function obtenerVentasPorSedeServiciosProductos($fecha) {
    $tabla_temporal = "TempVentasUnicas_" . $fecha;

    // Consulta para obtener cantidad total de servicios/productos y promedio de precio agrupados por sede
    $sql = "
        SELECT 
            SUM(CASE 
                WHEN sd.ServicioId IS NOT NULL THEN sd.Cantidad 
                ELSE 0 
            END) AS CantidadServicios,
            SUM(CASE 
                WHEN sd.ProductoId IS NOT NULL THEN sd.Cantidad 
                ELSE 0 
            END) AS CantidadProductos,
            IFNULL(AVG(sd.TotalConIGV / sd.Cantidad), 0) AS precio_promedio
        FROM 
            $tabla_temporal v
        INNER JOIN 
            sedes se ON v.TenantId = se.TenantId
        LEFT JOIN 
            sale_details sd ON v.SaleId = sd.SaleId
        ORDER BY 
            se.orden ASC;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);
    return $query->result_array();
}

public function obtenerVentasPorEspecieYSede($fecha) {
    $tabla_temporal = "TempVentasUnicas_" . $fecha;

    // Consulta para obtener la cantidad de ventas por especie y sede
    $sql = "
        SELECT 
            se.Nombre AS sede,
            COALESCE(ma.especie, 'Otros') AS EspecieMascota,  -- Si especie es NULL, mostrar 'Otros'
            COUNT(DISTINCT v.SaleId) AS TotalVentas
        FROM 
            $tabla_temporal v
        INNER JOIN 
            sedes se ON v.TenantId = se.TenantId
        LEFT JOIN 
            mascotas2 ma ON v.MascotaPatientId = ma.patient_id
        GROUP BY 
            se.Nombre, EspecieMascota  -- Agrupamos por sede y especie (con 'Otros' si es NULL)
        ORDER BY 
            se.Nombre, EspecieMascota;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);

    // Devolver los resultados
    return $query->result_array();
}
   // Método para eliminar la tabla temporal
    public function eliminarTablaTemporal($fecha) {
        $tabla_temporal = "TempVentasUnicas_" . $fecha;
        $sql = "DROP TEMPORARY TABLE IF EXISTS $tabla_temporal;";
        return $this->db->query($sql);
    }
}
