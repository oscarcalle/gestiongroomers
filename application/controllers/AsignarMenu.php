<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignarMenu extends CI_Controller {

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
            $this->load->view('asignar_menu', $data);
        }
        $this->load->view('__footer', $data);
    }

    // public function obtener_menus_tree($idnivel) {
    //     $menus = $this->AsignarMenu_model->get_menus_tree();
    //     $asignados = $this->AsignarMenu_model->obtener_menus_asignados($idnivel);
    //     $asignados_ids = array_column($asignados, 'idmenu');
    
    //     foreach ($menus as &$menu) {
    //         $menu['text'] = $menu['nombre'];
    //         $menu['state'] = ['selected' => in_array($menu['idmenu'], $asignados_ids)];
    //         $menu['id'] = 'j1_' . $menu['idmenu'];
    
    //         if (isset($menu['children'])) {
    //             foreach ($menu['children'] as &$submenu) {
    //                 $submenu['text'] = $submenu['nombre'];
    //                 $submenu['state'] = ['selected' => in_array($submenu['idmenu'], $asignados_ids)];
    //                 $submenu['id'] = 'j1_' . $submenu['idmenu'];
    //             }
    //         }
    //     }
    //     echo json_encode(array_values($menus));
    // }
    

    public function obtener_menus_tree($idnivel) {
        $menus = $this->AsignarMenu_model->get_menus_tree();
        $asignados = $this->AsignarMenu_model->obtener_menus_asignados($idnivel);
        $asignados_ids = array_column($asignados, 'idmenu');
    
        foreach ($menus as &$menu) {
            $menu['text'] = $menu['nombre'];
            $menu['id'] = 'j1_' . $menu['idmenu'];
            
            // Marcar el menú como seleccionado si está en la lista de asignados
            $menu['state'] = ['selected' => in_array($menu['idmenu'], $asignados_ids)];
    
            if (isset($menu['children'])) {
                foreach ($menu['children'] as &$submenu) {
                    $submenu['text'] = $submenu['nombre'];
                    $submenu['id'] = 'j1_' . $submenu['idmenu'];
    
                    // Marcar el submenu como seleccionado si está en la lista de asignados
                    $submenu['state'] = ['selected' => in_array($submenu['idmenu'], $asignados_ids)];
                }
            }
        }
        echo json_encode(array_values($menus));
    }
    
    
    
    
    
    public function asignar_menus() {
        $idnivel = $this->input->post('idnivel');
        $menus = $this->input->post('menus');
        
        // Verificar si $menus es un array y tiene elementos
        if (is_array($menus) && !empty($menus)) {
            //echo 'Menus received: ' . json_encode($menus) . "<br>";
    
            // Limpiar asignaciones existentes
            $this->AsignarMenu_model->eliminar_menus_por_nivel($idnivel);
            
            foreach ($menus as $idmenu) {
                //echo "Procesando menu con idmenu: $idmenu<br>";
    
                // Comprobar si el ID existe en la tabla 'menu2'
                if ($this->AsignarMenu_model->menu_existe($idmenu)) {
                    $query = $this->AsignarMenu_model->asignar_menu($idnivel, $idmenu);
                    
                    // Imprimir la consulta SQL generada
                    //echo "Consulta SQL ejecutada: " . $query . "<br>";
    
                    // Comprobar si la asignación fue exitosa
                    if (!$query) {
                        echo json_encode(['success' => false, 'message' => 'Error al asignar uno o más menús.']);
                        return;
                    }
                } else {
                    //echo "ID de menú no válido: $idmenu<br>";
                    
                }
            }
    
            // Si todo fue bien, devolver el éxito
            echo json_encode(['success' => true, 'message' => 'Menús asignados correctamente.']);
        } else {
            // Manejar el caso en que $menus no sea un array válido o esté vacío
            echo json_encode(['success' => false, 'message' => 'No se seleccionaron menús para asignar.']);
        }
    }
    
    
    
    
    
    
}
