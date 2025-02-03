<?php 
 
class Inicio extends CI_Controller{
 
	function __construct(){
		parent::__construct();
        $this->load->model('Menu_model'); // Cargar el modelo
	
		if($this->session->userdata('status') != "AezakmiHesoyamWhosyourdaddy"){
			redirect(base_url("Login"));
		}
	}
 
	function index(){
        // Obtener el nivel del usuario
		$idnivel_usuario = $this->session->userdata('idnivel');

		// Cargar el menú
		$data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);

        $this->load->view('__header', $data);
		$this->load->view('inicio', $data);
		$this->load->view('__footer', $data);
	}

}