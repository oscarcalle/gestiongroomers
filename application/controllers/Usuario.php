<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->model('Menu_model');
        $this->load->model('AsignarMenu_model');
        $this->load->model('Nivel_model'); // Carga el modelo de niveles
    }


    public function index() {
        // Obtener el nivel del usuario y la ruta actual
        $idnivel_usuario = $this->session->userdata('idnivel');
        $ruta = './' . $this->uri->uri_string();
    
        // Cargar el menú
        $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);

        //$data['usuarios'] = $this->Usuario_model->get_all();
        $data['usuarios'] = $this->Usuario_model->get_all_users();
        $data['niveles'] = $this->Nivel_model->get_all_levels(); // Asumiendo que tienes un modelo para niveles
        $data['niveles'] = $this->Usuario_model->get_niveles(); // Obtener niveles
        $data['sedes'] = $this->Usuario_model->get_sedes(); // Obtener sedes
    
        // Verificar acceso a la ruta
        if (!$this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta)) {
            $data['mensaje'] = "Usted no tiene privilegios para ver esta página.";
            $this->load->view('__header', $data);
            $this->load->view('error_view', $data);
        } else {
            $data['mensaje'] = "";
            $this->load->view('__header', $data);
            $this->load->view('usuario', $data);
        }
    
        // Cargar el pie de página al final
        $this->load->view('__footer', $data);
    }

    public function store() {
        $data = $this->input->post();
        $this->Usuario_model->insert($data);
        echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente.']);
    }

    public function update($id) {
        $data = $this->input->post();
        $this->Usuario_model->update($id, $data);
        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado exitosamente.']);
    }

    public function delete($id) {
        $this->Usuario_model->delete($id);
        echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado exitosamente.']);
    }

    public function get_by_id($id) {
        $usuario = $this->Usuario_model->get_usuario($id);
        // Asegúrate de que la consulta devuelva un valor para sedes
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }
    }
    
}
