<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_plansalud extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PlansaludModel'); // Si necesitas modelos adicionales, cárgalos aquí
    }

    public function index() {
        $data = $this->cargar_datos_comunes();
        
        $vista = $data['mensaje'] ? 'error_view' : 'dashboard_plansalud';
        $this->cargar_vistas($vista, $data);
    }

    public function top_mascotas() {
        $request = json_decode(file_get_contents('php://input'), true); 
        // $fecha_inicio = $request['start'] ?? null;
        // $fecha_fin = $request['end'] ?? null;
    
        // if (!$fecha_inicio || !$fecha_fin) {
        //     return $this->output_error('Fechas inválidas');
        // }

        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        
        $sql = "
        SELECT 
            se.nombre AS Sede, 
            ma.sexo, ma.especie,
            CONCAT(ma.apellido, ' ', ma.nombre) AS Mascota,
            COUNT(s.SaleId) AS Ventas,
            SUM(s.GlobalTotal) AS Total
        FROM (
            SELECT SaleId, TenantId, MascotaPatientId, GlobalTotal
            FROM sales
            WHERE FechaDelDocumento >= '2023-01-01 00:00:00'
                AND Anulado = 0
        ) s
        LEFT JOIN sedes se ON s.TenantId = se.TenantId
        LEFT JOIN mascotas2 ma ON s.MascotaPatientId = ma.patient_id AND ma.tenant_id = s.TenantId
        WHERE ma.fallecido = 0 
        $sedeClause
        GROUP BY se.nombre, s.MascotaPatientId, ma.apellido, ma.nombre
        ORDER BY Total DESC
        LIMIT 10;
        ";
    
        // Ejecutar la consulta con los parámetros de fecha
        $resultado = $this->db->query($sql)->result_array();
        //ma.fecha_actualizacion BETWEEN ? AND ?  and
        //$resultado = $this->db->query($sql, [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
        
        // Devolver el resultado en formato JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function piramide_mascotas() {
        $request = json_decode(file_get_contents('php://input'), true);    
        $sedeClause = $this->build_in_clause('tenant_id', $request['sedes'] ?? []);
        
        $sql = "
        SELECT 
            TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad,
            especie,
            sexo,
            COUNT(*) AS cantidad
        FROM mascotas2
        WHERE fallecido = 0 
        $sedeClause
        GROUP BY edad, especie, sexo
        ORDER BY edad DESC
        LIMIT 10;
        ";
    
        // Ejecutar la consulta con los parámetros de fecha
        $resultado = $this->db->query($sql)->result_array();
        
        // Devolver el resultado en formato JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    // Función para construir la cláusula de sede
    private function build_in_clause($column, $values) {
        // Si no se seleccionan valores o si se incluyen todas las sedes (en este caso, 6 sedes), no agregar la condición
        if (empty($values) || count($values) === 6) return '';
    
        // Escapar los valores y crear la cláusula IN
        $escaped_values = implode(',', array_map([$this->db, 'escape'], $values));
        return "AND $column IN ($escaped_values)";
    }    

    // Función para construir la cláusula de dia
    private function build_day_clause($dias) {
        // Si no se seleccionan días o se seleccionan todos los días, no agregar condición
        if (empty($dias) || count($dias) === 7) return '';
    
        $diasMap = [
            'lunes' => 'Monday', 'martes' => 'Tuesday', 'miercoles' => 'Wednesday',
            'jueves' => 'Thursday', 'viernes' => 'Friday', 'sabado' => 'Saturday', 'domingo' => 'Sunday'
        ];
        
        // Mapear los días seleccionados a su forma en inglés
        $diasIngles = array_filter(array_map(fn($dia) => $diasMap[$dia] ?? null, $dias));
        
        return empty($diasIngles) ? '' : "AND DAYNAME(FechaDelDocumento) IN ('" . implode("','", $diasIngles) . "')";
    }

    // Función para construir la cláusula de especie
    private function build_especie_clause($especies) {
        if (empty($especies)) return '';

        // Verificar si "Blanco" está en las especies y convertirlo a NULL
        $especies = array_map(function($especie) {
            return $especie === "Blanco" ? NULL : $especie;
        }, $especies);

        // Filtrar las especies que no son NULL
        $escaped_especies = array_filter($especies, function($especie) {
            return $especie !== NULL;
        });

        // Escapar las especies para la consulta
        $escaped_especies = implode(',', array_map([$this->db, 'escape'], $escaped_especies));

        // Crear la cláusula SQL base
        $clause = "AND (";

        // Si hay especies válidas (no NULL), agregar la cláusula IN
        if (!empty($escaped_especies)) {
            $clause .= "ma.especie IN ($escaped_especies)";
        }

        // Si "Blanco" estaba en las especies, agregar la condición de NULL para ma.especie
        if (in_array(NULL, $especies, true)) {
            // Si ya hay una cláusula IN, agregamos OR ma.especie IS NULL
            if (!empty($escaped_especies)) {
                $clause .= " OR ma.especie IS NULL";
            } else {
                // Si no hay especies válidas, solo agregamos la condición IS NULL
                $clause .= "ma.especie IS NULL";
            }
        }

        // Si no hay especies ni "Blanco", agregar la condición de MascotaPatientId IS NULL
        $clause .= " OR s.MascotaPatientId IS NULL)";

        return $clause;
    }

    private function build_turno_clause($turnos) {
        // Si no hay turnos o están todos (24 horas), no agregar condición
        if (empty($turnos) || count($turnos) === 24) {
            return '';
        }
    
        // Construir las condiciones para los turnos seleccionados
        $turnoConditions = array_map(function($turno) {
            list($start, $end) = explode('-', $turno);
            return "TIME(s.FechaDelDocumento) BETWEEN '$start' AND '$end'";
        }, $turnos);
    
        return "AND (" . implode(' OR ', $turnoConditions) . ")";
    }    
    
    // Función para construir la cláusula de área
    private function build_area_clause($areas) {
        if (empty($areas)) return '';
        $escaped_areas = implode(',', array_map([$this->db, 'escape'], $areas));
        return "AND (srv.Area IN ($escaped_areas) OR p.Area IN ($escaped_areas))";
    }

    // Método para mostrar error
    private function output_error($message) {
        echo json_encode(['error' => $message]);
        exit;
    }
}
