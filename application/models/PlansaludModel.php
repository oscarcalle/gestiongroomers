<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlansaludModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Asegúrate de que la base de datos esté cargada
    }

// Método para crear la tabla temporal
public function crearTablaTemporal(
    $fecha, $fecha_inicio, $fecha_fin, $sedeClause, $diaClause, $turnoClause, $especieClause, $areaClause
) {
    $tabla_temporal = "TempPlanesSaludUnicas_" . $fecha;

    // Consulta para crear la tabla temporal
    $sql = "
        CREATE TEMPORARY TABLE $tabla_temporal AS
    SELECT DISTINCT 
        s.SaleId, 
        s.FechaDelDocumento,
        s.UsuarioEmision,
        s.TenantId,
        se.Nombre AS Sede,
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
        FROM sale_details sd 
        INNER JOIN servicios srv ON sd.ServicioId = srv.ServicioId
        WHERE sd.SaleId = s.SaleId AND srv.Categoria = 'PLAN PROTECCION SALUD'
        ) AS Servicios,
        (SELECT GROUP_CONCAT(DISTINCT srv.Nombre SEPARATOR ', ') 
        FROM sale_details sd
        INNER JOIN servicios srv ON sd.ServicioId = srv.ServicioId
        WHERE sd.SaleId = s.SaleId AND srv.Categoria = 'PLAN PROTECCION SALUD'
        ) AS NombreServicio,
        (SELECT SUM(sd.Cantidad) FROM sale_details sd WHERE sd.SaleId = s.SaleId) AS CantidadTotal
    FROM 
        sales s
    INNER JOIN 
        sedes se ON s.TenantId = se.TenantId
    LEFT JOIN 
        mascotas2 ma ON s.MascotaPatientId = ma.patient_id AND ma.tenant_id = s.TenantId
    INNER JOIN 
        sale_details sd ON s.SaleId = sd.SaleId
    LEFT JOIN 
    servicios srv ON sd.ServicioId = srv.ServicioId
        WHERE 
            s.FechaDelDocumento BETWEEN ? AND ?
            AND srv.Categoria = 'PLAN PROTECCION SALUD' AND s.Anulado = 0
            $sedeClause
            $diaClause
            $turnoClause
            $especieClause
            $areaClause;
    ";

    // Ejecutar la creación de la tabla temporal
    return $this->db->query($sql, [$fecha_inicio, $fecha_fin]);
}


// Método para obtener ventas agrupadas por sede
public function obtenerVentasPorEspecie($fecha) {
    $tabla_temporal = "TempPlanesSaludUnicas_" . $fecha;

    // Consulta para obtener cantidad de transacciones, clientes únicos y total de ventas agrupados por sede
    $sql = "
        SELECT 
            v.especie as especie,
            COUNT(DISTINCT v.MascotaPatientId) AS TotalVentas
        FROM 
            $tabla_temporal v
        GROUP BY 
            v.especie
        ORDER BY 
            v.especie ASC;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);
    return $query->result_array();
}

// Método para obtener ventas agrupadas por planes
public function obtenerVentasPorPlanes($fecha) {
    $tabla_temporal = "TempPlanesSaludUnicas_" . $fecha;

    // Consulta para obtener las ventas agrupadas por área
    $sql = "
        SELECT 
            srv.Nombre AS PlanNombre,
            COUNT(DISTINCT v.SaleId) AS TotalVentas,
            SUM(v.TotalUnico) AS SumaVentas
        FROM 
            $tabla_temporal v
        INNER JOIN 
            sale_details sd ON v.SaleId = sd.SaleId
        INNER JOIN 
            servicios srv ON sd.ServicioId = srv.ServicioId
        WHERE 
            srv.Categoria = 'PLAN PROTECCION SALUD'
        GROUP BY 
            srv.Nombre
        ORDER BY 
            TotalVentas DESC;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);
    return $query->result_array();
}


public function obtenerVentasPorMesesySedes($fecha) {
    $tabla_temporal = "TempPlanesSaludUnicas_" . $fecha;

    // Array de los nombres de los meses en español
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];

    // Consulta optimizada para obtener cantidad total de ventas agrupadas por año, mes, sede y plan
    $sql = "
        SELECT 
            YEAR(v.FechaDelDocumento) AS Anio,
            MONTH(v.FechaDelDocumento) AS MesNumero,
            v.Sede,
            srv.Nombre AS PlanNombre,
            COUNT(DISTINCT v.SaleId) AS TotalVentas
        FROM 
            $tabla_temporal v
        INNER JOIN 
            sale_details sd ON v.SaleId = sd.SaleId
        INNER JOIN 
            servicios srv ON sd.ServicioId = srv.ServicioId
        WHERE 
            srv.Categoria = 'PLAN PROTECCION SALUD'
        GROUP BY 
            Anio, MesNumero, v.Sede, PlanNombre
        ORDER BY 
            Anio, MesNumero, v.Sede, PlanNombre;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);
    $result = $query->result_array();

    // Convertir el número de mes a nombre del mes en español
    foreach ($result as &$row) {
        $row['Mes'] = $meses[$row['MesNumero']];
    }

    return $result;
}


public function obtenerTopVendedores($fecha) {
    $tabla_temporal = "TempPlanesSaludUnicas_" . $fecha;

    // Consulta para obtener la cantidad de ventas por especie y sede
    $sql = "
        SELECT 
                v.UsuarioEmision,
                COUNT(DISTINCT v.SaleId) AS TotalTransacciones,
                SUM(v.TotalUnico) AS TotalVentas,
                v.Sede AS NombreSede
        FROM 
            $tabla_temporal v
         GROUP BY v.UsuarioEmision, v.Sede
            ORDER BY TotalVentas DESC
            LIMIT 10;
    ";

    // Ejecutar la consulta
    $query = $this->db->query($sql);

    // Devolver los resultados
    return $query->result_array();
}
   // Método para eliminar la tabla temporal
    public function eliminarTablaTemporal($fecha) {
        $tabla_temporal = "TempPlanesSaludUnicas_" . $fecha;
        $sql = "DROP TEMPORARY TABLE IF EXISTS $tabla_temporal;";
        return $this->db->query($sql);
    }




    // Obtener la cantidad de planes vigentes
    public function obtenerCantidadPlanesVigentes() {
        $this->db->where('NOW() BETWEEN fecha_inicio AND fecha_fin');
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results('planessalud2'); // Devuelve la cantidad de registros
    }

    // Obtener la cantidad de planes vencidos
    public function obtenerCantidadPlanesVencidos() {
        $this->db->where('fecha_fin < NOW()');
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results('planessalud2'); // Devuelve la cantidad de registros
    }

    // Obtener la cantidad de planes no renovados
    public function obtenerCantidadPlanesNoRenovados() {
        $this->db->where_not_exists(function($db) {
            $db->select('1')
               ->from('planessalud2 p2')
               ->where('p1.mascota_patient_id = p2.mascota_patient_id')
               ->where('p1.fecha_fin = p2.fecha_inicio');
        });
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results('planessalud2 p1'); // Devuelve la cantidad de registros
    }

    // Obtener la cantidad de planes por vencer en los próximos 30 días
    public function obtenerCantidadPlanesPorVencer() {
        $this->db->where('fecha_fin BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)');
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results('planessalud2'); // Devuelve la cantidad de registros
    }

    // Obtener la cantidad de planes nuevos creados en los últimos 30 días
    public function obtenerCantidadPlanesNuevos() {
        $this->db->where('fecha_inicio BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()');
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results('planessalud2'); // Devuelve la cantidad de registros
    }

    

    // // Obtener planes vigentes
    // public function obtenerPlanesVigentes() {
    //     $this->db->where('NOW() BETWEEN fecha_inicio AND fecha_fin');
    //     $this->db->where('is_deleted', 0);
    //     return $this->db->get('planessalud2')->result_array();
    // }

    // // Obtener planes vencidos
    // public function obtenerPlanesVencidos() {
    //     $this->db->where('fecha_fin < NOW()');
    //     $this->db->where('is_deleted', 0);
    //     return $this->db->get('planessalud2')->result_array();
    // }

    // // Obtener planes no renovados
    // public function obtenerPlanesNoRenovados() {
    //     $this->db->where_not_exists(function($db) {
    //         $db->select('1')
    //            ->from('planessalud2 p2')
    //            ->where('p1.mascota_patient_id = p2.mascota_patient_id')
    //            ->where('p1.fecha_fin = p2.fecha_inicio');
    //     });
    //     $this->db->where('is_deleted', 0);
    //     return $this->db->get('planessalud2 p1')->result_array();
    // }

    // // Obtener planes por vencer en los próximos 30 días
    // public function obtenerPlanesPorVencer() {
    //     $this->db->where('fecha_fin BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)');
    //     $this->db->where('is_deleted', 0);
    //     return $this->db->get('planessalud2')->result_array();
    // }

    // // Obtener planes nuevos creados en los últimos 30 días
    // public function obtenerPlanesNuevos() {
    //     $this->db->where('fecha_inicio BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()');
    //     $this->db->where('is_deleted', 0);
    //     return $this->db->get('planessalud2')->result_array();
    // }
}
