<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    private function get_all_submenus($idnivel) {
        $this->db->select('menu.*, menu.padre_id as parent_id');
        $this->db->from('menu');
        $this->db->join('nivel_menu', 'menu.idmenu = nivel_menu.idmenu');
        $this->db->where('menu.estado', 'Activo');
        $this->db->where('nivel_menu.idnivel', $idnivel);
        return $this->db->get()->result_array();
    }
    
    public function cargar_menu($idnivel_usuario) {
        // 1. Traer todos los menús accesibles por ese nivel
        $this->db->select('menu.*, menu.padre_id as parent_id');
        $this->db->from('menu');
        $this->db->join('nivel_menu', 'menu.idmenu = nivel_menu.idmenu');
        $this->db->where('menu.estado', 'Activo');
        
        if ($idnivel_usuario != 1) {
            $this->db->where('nivel_menu.idnivel', $idnivel_usuario);
        }
    

        $this->db->order_by('COALESCE(menu.padre_id, 0) ASC, menu.orden ASC');

        $menus = $this->db->get()->result_array();
    
        // 2. Agrupar menús por padre_id
        $menu_tree = [];
        $menu_index = [];
    
        foreach ($menus as $menu) {
            $menu['submenus'] = [];
            $menu_index[$menu['idmenu']] = $menu;
        }
    
        // 3. Construir árbol
        foreach ($menu_index as $id => &$menu) {
            if ($menu['parent_id']) {
                $menu_index[$menu['parent_id']]['submenus'][] = &$menu;
            } else {
                $menu_tree[] = &$menu;
            }
        }
    
        return $menu_tree;
    }    
    

    private function get_submenus($idmenu, $idnivel) {
        $this->db->select('menu.*');
        $this->db->from('menu');
        $this->db->join('nivel_menu', 'menu.idmenu = nivel_menu.idmenu');
        $this->db->where('nivel_menu.idnivel', $idnivel);
        $this->db->where('menu.estado', 'Activo');
        $this->db->where('menu.padre_id', $idmenu);
        return $this->db->get()->result_array();
    }

    public function get_all_menus() {
        return $this->db->get('menu')->result_array();
    }

    public function get_menu_by_id($id) {
        return $this->db->get_where('menu', ['idmenu' => $id])->row_array();
    }

    public function insert_menu($data) {
        return $this->db->insert('menu', $data);
    }

    public function update_menu($data) {
        return $this->db->update('menu', $data, ['idmenu' => $data['idmenu']]);
    }

    public function delete_menu($id) {
        return $this->db->delete('menu', ['idmenu' => $id]);
    }
}
