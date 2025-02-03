<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Metas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        
        if ($this->session->userdata('status') !== "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
        $this->load->model('Meta_model');
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

        $data['metas'] = $this->Meta_model->get_all();
        $this->load->view('__header', $data);
        $this->load->view($data['mensaje'] ? 'error_view' : 'metas', $data);
        $this->load->view('__footer');
    }


    public function create() {
        // Esto se manejará mediante el modal, no es necesario método para vista
    }

    public function store() {
        $data = [
            'idsede' => $this->input->post('idsede'),
            'mes' => $this->input->post('mes'),
            'anio' => $this->input->post('anio'),
            'meta' => $this->input->post('meta'),
        ];
        
        $idmeta = $this->input->post('idmeta');
        if ($idmeta) {
            $this->Meta_model->update($idmeta, $data);
        } else {
            $idmeta = $this->Meta_model->insert($data);
        }
        echo json_encode(['success' => true, 'idmeta' => $idmeta]);
    }

    public function edit($id) {
        $meta = $this->Meta_model->get($id);
        echo json_encode($meta);
    }

    public function delete($id) {
        $this->Meta_model->delete($id);
        echo json_encode(['success' => true]);
    }
}
