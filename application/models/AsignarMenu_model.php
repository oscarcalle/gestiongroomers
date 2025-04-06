<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignarMenu_model extends CI_Model {

    public function tiene_privilegio($idnivel, $ruta) {
        // Si el nivel es 1, retornar true directamente
        if ($idnivel == 1) {
            return true;
        }
    
        // Obtener el idmenu correspondiente a la ruta
        $this->db->select('idmenu');
        $this->db->from('menu');
        $this->db->where('ruta', $ruta);
        $menu = $this->db->get()->row_array();
    
        // Si no se encontró el menú, retornar false
        if (!$menu) {
            return false; // No se encontró el menú correspondiente a la ruta
        }
    
        // Verificar si el menú está asignado al nivel
        $this->db->from('nivel_menu');
        $this->db->where('idnivel', $idnivel);
        $this->db->where('idmenu', $menu['idmenu']);
        $asignado = $this->db->get()->num_rows() > 0;
    
        return $asignado; // Retorna true si está asignado, false en caso contrario
    }

    public function asignar_menu($idnivel, $idmenu) {
        // Preparar los datos para la inserción
        $data = array(
            'idnivel' => $idnivel,
            'idmenu' => $idmenu
        );
    
        // Realizar la inserción
        $this->db->insert('nivel_menu', $data);
    
        // Captura la consulta SQL generada
        $last_query = $this->db->last_query();  // Captura la última consulta SQL
    
        // Devolver la consulta SQL
        return $last_query;
    }
    
    
    public function get_niveles() {
        $this->db->select('*');
        $this->db->from('nivel');
        return $this->db->get()->result_array();
    }

    public function get_menus() {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where('estado', 'Activo');
        return $this->db->get()->result_array();
    }

    public function obtener_menus_asignados($idnivel) {
        $this->db->select('menu.*');
        $this->db->from('nivel_menu');
        $this->db->join('menu', 'nivel_menu.idmenu = menu.idmenu');
        $this->db->where('nivel_menu.idnivel', $idnivel);
        return $this->db->get()->result_array();
    }

    // public function get_menus_tree() {
    //     $this->db->select('idmenu, nombre, ruta, padre_id');
    //     $this->db->from('menu');
    //     $this->db->where('estado', 'Activo');
    //     $result = $this->db->get()->result_array();
    
    //     $tree = [];
    //     foreach ($result as $menu) {
    //         if ($menu['padre_id'] == null) {
    //             $tree[$menu['idmenu']] = $menu;
    //             $tree[$menu['idmenu']]['children'] = [];
    //         } else {
    //             $tree[$menu['padre_id']]['children'][] = $menu;
    //         }
    //     }
    
    //     return $tree;
    // }

    public function get_menus_tree() {
        // Obtener todos los menús y submenús de la base de datos
        $this->db->select('idmenu, nombre, ruta, padre_id');
        $this->db->from('menu');
        $this->db->where('estado', 'Activo');
        $this->db->order_by('COALESCE(padre_id, 0) ASC, orden ASC', null); // segundo parámetro "null" es clave
        $result = $this->db->get()->result_array();
        
        $tree = [];
        foreach ($result as $menu) {
            if ($menu['padre_id'] == null) {
                $tree[$menu['idmenu']] = $menu;
                $tree[$menu['idmenu']]['children'] = [];
            } else {
                $tree[$menu['padre_id']]['children'][] = $menu;
            }
        }
    
        // Convertir a un array de valores para que jstree lo procese
        return array_values($tree);
    }
    

    public function eliminar_menus_por_nivel($idnivel) {
        $this->db->where('idnivel', $idnivel);
        $this->db->delete('nivel_menu');
    }

    public function menu_existe($idmenu) {
        $this->db->from('menu');
        $this->db->where('idmenu', $idmenu);
        return $this->db->count_all_results() > 0;
    }
    
    
    
}
