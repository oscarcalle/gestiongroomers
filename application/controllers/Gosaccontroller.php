<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gosaccontroller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos el modelo
        $this->load->model('VentasModel');
    }

    private function build_in_clause($column, $values) {
        // Definir el mapeo de números a nombres
        $map = [
            3121 => 'Benavides',
            1037 => 'Jorge Chavez',
            3628 => 'La Molina',
            1042 => 'Magdalena',
            3247 => 'San Borja',
            3063 => 'Pet Movil',
        ];
    
        // Validar y convertir los valores
        $converted_values = [];
        foreach ($values as $value) {
            if (is_numeric($value) && isset($map[$value])) {
                $converted_values[] = $this->db->escape($map[$value]);
            }
        }
    
        // Si no hay valores válidos, retornar cadena vacía
        if (empty($converted_values)) return '';
    
        // Construir la cláusula IN
        $escaped_values = implode(',', $converted_values);
        return "AND $column IN ($escaped_values)";
    }
    

    private function build_day_clause($dias) {
        if (empty($dias)) return '';
        $diasMap = [
            'lunes' => 'Monday', 'martes' => 'Tuesday', 'miercoles' => 'Wednesday',
            'jueves' => 'Thursday', 'viernes' => 'Friday', 'sabado' => 'Saturday', 'domingo' => 'Sunday'
        ];
        $diasIngles = array_filter(array_map(fn($dia) => $diasMap[$dia] ?? null, $dias));
        return empty($diasIngles) ? '' : "AND DAYNAME(s.fecha_venta) IN ('" . implode("','", $diasIngles) . "')";
    }

    private function build_turno_clause($turnos) {
        if (empty($turnos)) return '';
        $turnoConditions = array_map(function($turno) {
            list($start, $end) = explode('-', $turno);
            return "TIME(s.fecha_venta) BETWEEN '$start' AND '$end'";
        }, $turnos);
        return "AND (" . implode(' OR ', $turnoConditions) . ")";
    }
    
    // Función para construir la cláusula de área
    private function build_area_clause($areas) {
        if (empty($areas)) return '';
        $escaped_areas = implode(',', array_map([$this->db, 'escape'], $areas));
        return "AND s.area IN ($escaped_areas) ";
    }

    // Método para mostrar error
    private function output_error($message) {
        echo json_encode(['error' => $message]);
        exit;
    }


    /************************* GOSAC ****************************/

    public function obtenerVentas_gosac() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.sucursal', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
    
        $sql = "
        SELECT 
            se.Nombre AS sede,
            COUNT(DISTINCT s.id) AS TotalVentas,
            COUNT(DISTINCT s.nombre_cliente COLLATE utf8mb4_unicode_ci) 
            + COUNT(DISTINCT s.ruc_documento COLLATE utf8mb4_unicode_ci) AS TotalClientesUnicos,
            SUM(s.precio_vta_tot) AS SumaVentas
        FROM 
            gosac s
        INNER JOIN 
            sedes se ON s.sucursal COLLATE utf8mb4_unicode_ci = se.Nombre COLLATE utf8mb4_unicode_ci
            WHERE 
                s.fecha_venta BETWEEN ? AND ?
                $sedeClause
                $diaClause
                $turnoClause
                $areaClause
        GROUP BY 
            s.sucursal
        ORDER BY 
            se.orden ASC;
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function serviciosYprecioPromedio_gosac() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.sucursal', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
    
        $sql = "
        SELECT  0 AS CantidadServicios, COUNT(s.nombre) AS CantidadProductos, AVG(precio_vta_tot) AS precio_promedio
        FROM  gosac s
            WHERE 
                s.fecha_venta BETWEEN ? AND ?
                $sedeClause
                $diaClause
                $turnoClause
                $areaClause
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

public function total_ventas_por_area_gosac() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.sucursal', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
    
        $sql = "
        SELECT   'Gosac' AS empresa, AREA, SUM(precio_vta_tot) AS total_ventas
        FROM  gosac s
        WHERE 
                s.fecha_venta BETWEEN ? AND ?
                $sedeClause
                $diaClause
                $turnoClause
                $areaClause
        GROUP BY  AREA
        ORDER BY total_ventas DESC;
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }


    public function area_categoria_nombre_gosac() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.sucursal', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
    
        $sql = "
        SELECT 
                'Gosac' AS empresa,
                s.area AS AREA,
                s.categoria AS Categoria,
                s.nombre AS Nombre,
                SUM(CASE WHEN s.sucursal = 'Benavides' THEN s.cantidad ELSE 0 END) AS Benavides_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'Jorge Chavez' THEN s.cantidad ELSE 0 END) AS JorgeChavez_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'SanBorja' THEN s.cantidad ELSE 0 END) AS SanBorja_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'LaMolina' THEN s.cantidad ELSE 0 END) AS LaMolina_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'Magdalena' THEN s.cantidad ELSE 0 END) AS Magdalena_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'PetMovil' THEN s.cantidad ELSE 0 END) AS PetMovil_TotalServiciosProductos,
                SUM(s.cantidad) AS TotalGeneral_ServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'Benavides' THEN s.precio_vta_tot ELSE 0 END) AS Benavides_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'Jorge Chavez' THEN s.precio_vta_tot ELSE 0 END) AS JorgeChavez_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'SanBorja' THEN s.precio_vta_tot ELSE 0 END) AS SanBorja_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'LaMolina' THEN s.precio_vta_tot ELSE 0 END) AS LaMolina_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'Magdalena' THEN s.precio_vta_tot ELSE 0 END) AS Magdalena_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'PetMovil' THEN s.precio_vta_tot ELSE 0 END) AS PetMovil_TotalConIGV,
                SUM(s.precio_vta_tot) AS TotalGeneral_TotalConIGV
            FROM 
                gosac s
                    WHERE 
                            s.fecha_venta BETWEEN ? AND ?
                            $sedeClause
                            $diaClause
                            $turnoClause
                            $areaClause
            GROUP BY 
                s.area, s.categoria, s.nombre
            ORDER BY 
                s.area, s.categoria, s.nombre;
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }


    public function area_categoria_gosac() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.sucursal', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
    
        $sql = "
        SELECT 
                'Gosac' AS empresa,
                s.area AS AREA,
                s.categoria AS Categoria,
                SUM(CASE WHEN s.sucursal = 'Benavides' THEN s.cantidad ELSE 0 END) AS Benavides_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'Jorge Chavez' THEN s.cantidad ELSE 0 END) AS JorgeChavez_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'SanBorja' THEN s.cantidad ELSE 0 END) AS SanBorja_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'LaMolina' THEN s.cantidad ELSE 0 END) AS LaMolina_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'Magdalena' THEN s.cantidad ELSE 0 END) AS Magdalena_TotalServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'PetMovil' THEN s.cantidad ELSE 0 END) AS PetMovil_TotalServiciosProductos,
                SUM(s.cantidad) AS TotalGeneral_ServiciosProductos,
                SUM(CASE WHEN s.sucursal = 'Benavides' THEN s.precio_vta_tot ELSE 0 END) AS Benavides_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'Jorge Chavez' THEN s.precio_vta_tot ELSE 0 END) AS JorgeChavez_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'SanBorja' THEN s.precio_vta_tot ELSE 0 END) AS SanBorja_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'LaMolina' THEN s.precio_vta_tot ELSE 0 END) AS LaMolina_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'Magdalena' THEN s.precio_vta_tot ELSE 0 END) AS Magdalena_TotalConIGV,
                SUM(CASE WHEN s.sucursal = 'PetMovil' THEN s.precio_vta_tot ELSE 0 END) AS PetMovil_TotalConIGV,
                SUM(s.precio_vta_tot) AS TotalGeneral_TotalConIGV
            FROM 
                gosac s
                    WHERE 
                            s.fecha_venta BETWEEN ? AND ?
                            $sedeClause
                            $diaClause
                            $turnoClause
                            $areaClause
            GROUP BY 
                s.area, s.categoria
            ORDER BY 
                s.area, s.categoria
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }


    public function obtenerVentas() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.sucursal', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
    
        $sql = "
        SELECT 
                s.sucursal AS sede,
                COUNT(DISTINCT CONCAT(s.serie, s.correlativo)) AS TotalVentas,
                COUNT(DISTINCT s.ruc_documento) AS TotalClientesUnicos,
                SUM(s.valor_vta_tot) AS SumaVentas
            FROM 
                gosac s
                    WHERE 
                            s.fecha_venta BETWEEN ? AND ?
                            $sedeClause
                            $diaClause
                            $turnoClause
                            $areaClause
            GROUP BY sucursal
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

   

    public function resumen()
    {
         // Consulta 1: Total de ventas por sucursal
        $query1 = $this->db->query("
            SELECT sucursal, SUM(valor_vta_tot) AS total_ventas 
            FROM gosac 
            GROUP BY sucursal
        ");
        $data['ventas_por_sucursal'] = $query1->result_array();

        // Consulta 2: Ventas diarias (últimos 7 días)
        $query2 = $this->db->query("
            SELECT DATE(fecha_venta) AS fecha, SUM(valor_vta_tot) AS total_diario 
            FROM gosac 
            WHERE fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fecha_venta)
        ");
        $data['ventas_diarias'] = $query2->result_array();

        // Consulta 3: Productos más vendidos
        $query3 = $this->db->query("
            SELECT nombre, SUM(cantidad) AS total_vendido 
            FROM gosac 
            GROUP BY nombre 
            ORDER BY total_vendido DESC 
            LIMIT 5
        ");
        $data['productos_mas_vendidos'] = $query3->result_array();

        // Consulta 4: Ventas por categoría
        $query4 = $this->db->query("
            SELECT categoria, SUM(valor_vta_tot) AS total_categoria 
            FROM gosac 
            GROUP BY categoria
        ");
        $data['ventas_por_categoria'] = $query4->result_array();

        // Consulta 5: Ventas por día de la semana
        $query5 = $this->db->query("
            SELECT DAYNAME(fecha_venta) AS dia, SUM(valor_vta_tot) AS total_dia 
            FROM gosac 
            GROUP BY DAYNAME(fecha_venta)
            ");
        $data['ventas_por_dia'] = $query5->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }



    
}

