<?php 

class Configurarmenu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Menu_model'); // Cargar el modelo
        $this->load->model('AsignarMenu_model');

        // Verificar la sesión del usuario
        if ($this->session->userdata('status') != "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
    }

    public function index() {
        // Obtener el nivel del usuario y la ruta actual
        $idnivel_usuario = $this->session->userdata('idnivel');
        $ruta = './' . $this->uri->uri_string();
    
        // Cargar el menú
        $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);
        $data['todos_menus'] = $this->Menu_model->get_all_menus();
    
        // Verificar acceso a la ruta
        if (!$this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta)) {
            $data['mensaje'] = "Usted no tiene privilegios para ver esta página.";
            $this->load->view('__header', $data);
            $this->load->view('error_view', $data);
        } else {
            $data['mensaje'] = "";
            $this->load->view('__header', $data);
            $this->load->view('configurar_menu', $data);
        }
    
        // Cargar el pie de página al final
        $this->load->view('__footer', $data);
    }
    

    public function get_menu($id) {
        $menu = $this->Menu_model->get_menu_by_id($id);
        echo json_encode($menu);
    }

    public function save_menu() {
        $data = $this->input->post();
        $action = isset($data['idmenu']) && !empty($data['idmenu']) ? 'update' : 'insert';

        // Usar un método de modelo para insertar o actualizar
        if ($action === 'update') {
            $this->Menu_model->update_menu($data);
        } else {
            $this->Menu_model->insert_menu($data);
        }

        redirect('Configurarmenu'); // Redirigir después de guardar
    }

    public function delete_menu($id) {
        if ($this->Menu_model->delete_menu($id)) {
            // Eliminar con éxito
            redirect('Configurarmenu');
        } else {
            // Manejar el error si es necesario
            $this->session->set_flashdata('error', 'No se pudo eliminar el menú.');
            redirect('Configurarmenu');
        }
    }
}
