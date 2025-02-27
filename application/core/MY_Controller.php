<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        // Cargar modelos comunes
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        
        // Verificar sesión
        if ($this->session->userdata('status') !== "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
    }

    protected function cargar_datos_comunes() {
        $idnivel_usuario = $this->session->userdata('idnivel');
        $sedes_usuario = $this->session->userdata('sedes');
        $ruta = './' . $this->uri->uri_string();
        
        $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);
        $data['mensaje'] = $this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta) ? "" : "Usted no tiene privilegios para ver esta página.";
        
        // Asegúrate de que $sedes_usuario sea un array
        if (!is_array($sedes_usuario)) {
            if (!empty($sedes_usuario) && is_string($sedes_usuario)) {
                $sedes_usuario = explode(',', $sedes_usuario);
            } else {
                $sedes_usuario = [];
            }
        }

        // Construcción de la consulta
        $this->db->where('estado', 'Habilitado');
        if ($idnivel_usuario != 1) {
            $this->db->where_in('TenantId', $sedes_usuario);
        }

        $data['sedes'] = $this->db->get('sedes')->result_array();

        return $data;
    }

    protected function cargar_vistas($vista, $data = []) {
        $this->load->view('__header', $data);
        $this->load->view($vista, $data);
        $this->load->view('__footer');
    }
}
