<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Comparativo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        
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
        $this->load->view($data['mensaje'] ? 'error_view' : 'comparativo', $data);
        $this->load->view('__footer');
    }

    // Otras funciones como build_in_clause, build_day_clause y build_turno_clause
    private function output_error($message) {
        $this->output->set_content_type('application/json')->set_output(json_encode(['error' => $message]));
    }

    private function build_in_clause($column, $values) {
        if (empty($values)) return '';
        $escaped_values = implode(',', array_map([$this->db, 'escape'], $values));
        return "AND $column IN ($escaped_values)";
    }

    private function build_day_clause($dias) {
        if (empty($dias)) return '';
        $diasMap = [
            'lunes' => 'Monday', 'martes' => 'Tuesday', 'miercoles' => 'Wednesday',
            'jueves' => 'Thursday', 'viernes' => 'Friday', 'sabado' => 'Saturday', 'domingo' => 'Sunday'
        ];
        $diasIngles = array_filter(array_map(fn($dia) => $diasMap[$dia] ?? null, $dias));
        return empty($diasIngles) ? '' : "AND DAYNAME(s.FechaDelDocumento) IN ('" . implode("','", $diasIngles) . "')";
    }

    private function build_turno_clause($turnos) {
        if (empty($turnos)) return '';
        $turnoConditions = array_map(function($turno) {
            list($start, $end) = explode('-', $turno);
            return "TIME(s.FechaDelDocumento) BETWEEN '$start' AND '$end'";
        }, $turnos);
        return "AND (" . implode(' OR ', $turnoConditions) . ")";
    }
    
    
}
?>