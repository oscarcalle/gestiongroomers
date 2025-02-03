<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard_plansalud extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        $this->load->model('PlansaludModel');
        
        if ($this->session->userdata('status') !== "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
    }

        public function index() {
            $idnivel_usuario = $this->session->userdata('idnivel');
            $sedes_usuario = $this->session->userdata('sedes');
            $ruta = './' . $this->uri->uri_string();
            $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);
            $data['mensaje'] = $this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta) ? "" : "Usted no tiene privilegios para ver esta página.";
            // Asegúrate de que $sedes_usuario sea un array
            if (!is_array($sedes_usuario)) {
                if (!empty($sedes_usuario) && is_string($sedes_usuario)) {
                    $sedes_usuario = explode(',', $sedes_usuario); // Convierte en array si es una cadena separada por comas
                } else {
                    $sedes_usuario = []; // Si es null u otro tipo, inicializa como un array vacío
                }
            }

            // Construcción de la consulta
            $this->db->where('estado', 'Habilitado');

            // Si el nivel de usuario no es 1, agrega el filtro TenantId
            if ($idnivel_usuario != 1) {
                $this->db->where_in('TenantId', $sedes_usuario);
            }

            $data['sedes'] = $this->db->get('sedes')->result_array();

            $this->load->view('__header', $data);
            $this->load->view($data['mensaje'] ? 'error_view' : 'dashboard_plansalud', $data);
            $this->load->view('__footer');
        }
    
        // Método para obtener ventas agrupadas por sede
        public function obtenerVentas() {
            $request = json_decode(file_get_contents('php://input'), true);
            
            // Validar y procesar fechas
            $fecha_inicio = isset($request['start']) && strtotime($request['start']) ? $request['start'] . " 00:00:00" : null;
            $fecha_fin = isset($request['end']) && strtotime($request['end']) ? $request['end'] . " 23:59:59" : null;
        
            if (!$fecha_inicio || !$fecha_fin) {
                return $this->output_error('Fechas inválidas');
            }
        
            // Generar cláusulas dinámicas
            $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
            $diaClause = $this->build_day_clause($request['dias'] ?? []);
            $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
            $especieClause = $this->build_especie_clause($request['especies'] ?? []);
            $areaClause = $this->build_area_clause($request['areas'] ?? []);

            // Fecha para la tabla temporal
            $fecha = date('Ymd'); // Formato YYYYMMDD
        
            try {
                // Crear tabla temporal
                $this->PlansaludModel->crearTablaTemporal($fecha, $fecha_inicio, $fecha_fin, $sedeClause, $diaClause, $turnoClause, $especieClause, $areaClause);
        
                // Obtener datos
                $resultado = [
                    'ventasPorEspecie' => $this->PlansaludModel->obtenerVentasPorEspecie($fecha),
                    'ventasPorPlanes' => $this->PlansaludModel->obtenerVentasPorPlanes($fecha),
                    'ventasPorMesesYSedes' => $this->PlansaludModel->obtenerVentasPorMesesySedes($fecha),
                    'topVendedores' => $this->PlansaludModel->obtenerTopVendedores($fecha)
                ];
            } catch (Exception $e) {
                return $this->output_error('Error al procesar datos: ' . $e->getMessage());
            } finally {
                // Eliminar tabla temporal
                $this->PlansaludModel->eliminarTablaTemporal($fecha);
            }
        
            // Responder con los resultados
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
        }

        public function cantidad_motivo_noventa() {
            $request = json_decode(file_get_contents('php://input'), true);
            
            // Validar y procesar fechas
            $fecha_inicio = isset($request['start']) && strtotime($request['start']) ? $request['start'] . " 00:00:00" : null;
            $fecha_fin = isset($request['end']) && strtotime($request['end']) ? $request['end'] . " 23:59:59" : null;
        
            if (!$fecha_inicio || !$fecha_fin) {
                return $this->output_error('Fechas inválidas');
            }
        
            // Generar cláusulas dinámicas
            $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);

            $sql = "
            SELECT 
                CASE 
                    WHEN motivo IS NULL THEN 'No Gestionado'
                    WHEN motivo = '' THEN 'No especificado'
                    ELSE motivo 
                END AS motivo,
                COUNT(*) AS cantidad
            FROM `planes_contacto_cache`
            WHERE fecha_fin < ?
                AND estado_renovacion = 'No Renovado' 
            GROUP BY 
                CASE 
                    WHEN motivo IS NULL THEN 'No Gestionado'
                    WHEN motivo = '' THEN 'No especificado'
                    ELSE motivo 
                END
            ORDER BY cantidad DESC;
            ";

            $resultado = $this->db->query($sql, [$fecha_fin])->result_array();
        
            // Responder con los resultados
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
        }

        public function reporte_planes_vencidos() {
            $request = json_decode(file_get_contents('php://input'), true);
            
            // Validar y procesar fechas
            $fecha_inicio = isset($request['start']) && strtotime($request['start']) ? $request['start'] . " 00:00:00" : null;
            $fecha_fin = isset($request['end']) && strtotime($request['end']) ? $request['end'] . " 23:59:59" : null;
        
            if (!$fecha_inicio || !$fecha_fin) {
                return $this->output_error('Fechas inválidas');
            }
        
            // Generar cláusulas dinámicas
            $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);

            $sql = "
            SELECT 
                COUNT(CASE WHEN fecha_fin < ? THEN 1 END) AS clientes_vencidos,
                COUNT(CASE WHEN estado_renovacion = 'Renovado' THEN 1 END) AS clientes_renovaron,
                COUNT(CASE WHEN estado_renovacion = 'No Renovado' THEN 1 END) AS clientes_no_renovaron,
                COUNT(CASE WHEN contactado IS NULL THEN 1 END) AS clientes_por_gestionar
            FROM `planes_contacto_cache`;
            ";

            $resultado = $this->db->query($sql, [$fecha_fin])->result_array();
        
            // Responder con los resultados
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
        }



        private function build_in_clause($column, $values) {
            // Si no se seleccionan valores o si se incluyen todas las sedes (en este caso, 6 sedes), no agregar la condición
            if (empty($values) || count($values) === 6) return '';
        
            // Escapar los valores y crear la cláusula IN
            $escaped_values = implode(',', array_map([$this->db, 'escape'], $values));
            return "AND $column IN ($escaped_values)";
        }    
    
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
            return "AND srv.Area IN ($escaped_areas)";
        }
    
        // Método para mostrar error
        private function output_error($message) {
            echo json_encode(['error' => $message]);
            exit;
        }
    
}
?>