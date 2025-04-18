<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intranet extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Menu_model'); 
        $this->load->model('AsignarMenu_model');

        if($this->session->userdata('status') != "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
    }

    public function index() {
        // Obtener el nivel del usuario
        $idnivel_usuario = $this->session->userdata('idnivel');
        $ruta = './' . $this->uri->uri_string();

        // Cargar el menú
        $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);

        $data['niveles'] = $this->AsignarMenu_model->get_niveles();
        $data['menus'] = $this->AsignarMenu_model->get_menus();

            // Verificar acceso a la ruta
        if (!$this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta)) {
            $data['mensaje'] = "Usted no tiene privilegios para ver esta página.";
            $this->load->view('__header', $data);
            $this->load->view('error_view', $data);
        } else {
            $data['mensaje'] = "";
            $this->load->view('__header', $data);
            $this->load->view('intranet', $data);
        }
        $this->load->view('__footer', $data);
    }

}
