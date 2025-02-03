<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Inicioasistencia extends CI_Controller {

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
        $this->load->view($data['mensaje'] ? 'error_view' : 'inicioasistencia', $data);
        $this->load->view('__footer');
    }

    public function registrar_asistencia() {
        $this->load->model('Asistencia_model'); // Carga el modelo de asistencia
        $empleado_id = $this->session->userdata('empleado_id');
        $tipo_marcaje = $this->input->post('tipo_marcaje'); // Puede ser 'entrada', 'salida_refrigerio', etc.
        $hora_actual = date('Y-m-d H:i:s');
        $fecha_actual = date('Y-m-d');
    
        // Verifica si ya existe un registro para hoy
        $registro_existente = $this->Asistencia_model->obtener_asistencia($empleado_id, $fecha_actual);
    
        if ($registro_existente) {
            // Actualiza el campo correspondiente según el tipo de marcaje
            $actualizar_datos = [
                $tipo_marcaje => $hora_actual
            ];
            $this->Asistencia_model->actualizar_asistencia($registro_existente['asistencia_id'], $actualizar_datos);
        } else {
            // Crea un nuevo registro
            $nuevo_datos = [
                'empleado_id' => $empleado_id,
                'fecha' => $fecha_actual,
                $tipo_marcaje => $hora_actual
            ];
            $this->Asistencia_model->insertar_asistencia($nuevo_datos);
        }
    
        // Devuelve el estado actualizado
        $asistencia_actualizada = $this->Asistencia_model->obtener_asistencia($empleado_id, $fecha_actual);
        echo json_encode($asistencia_actualizada);
    }
    
    
}
?>