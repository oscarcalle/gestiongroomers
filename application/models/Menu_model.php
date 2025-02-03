<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    private function get_all_submenus($idnivel) {
        $this->db->select('menu2.*, menu2.padre_id as parent_id');
        $this->db->from('menu2');
        $this->db->join('nivel_menu', 'menu2.idmenu = nivel_menu.idmenu');
        $this->db->where('menu2.estado', 'Activo');
        $this->db->where('nivel_menu.idnivel', $idnivel);
        return $this->db->get()->result_array();
    }
    
    public function cargar_menu($idnivel_usuario) {
        // Obtener menús principales
        $this->db->select('menu2.*');
        $this->db->from('menu2');
        $this->db->join('nivel_menu', 'menu2.idmenu = nivel_menu.idmenu');
    
        if ($idnivel_usuario != 1) {
            $this->db->where('nivel_menu.idnivel', $idnivel_usuario);
        }
    
        $this->db->where('menu2.estado', 'Activo');
        $this->db->where('(menu2.padre_id IS NULL OR menu2.padre_id = "")');
        $this->db->group_by('menu2.idmenu');
        $menu_principal = $this->db->get()->result_array();
    
        // Obtener submenús en una sola consulta
        $submenus = $this->get_all_submenus($idnivel_usuario);
        $submenu_grouped = [];
        foreach ($submenus as $submenu) {
            $submenu_grouped[$submenu['parent_id']][] = $submenu;
        }
    
        // Asociar submenús con menús principales
        foreach ($menu_principal as &$menu) {
            $menu['submenus'] = $submenu_grouped[$menu['idmenu']] ?? [];
        }
    
        return $menu_principal;
    }
    

    private function get_submenus($idmenu, $idnivel) {
        $this->db->select('menu2.*');
        $this->db->from('menu2');
        $this->db->join('nivel_menu', 'menu2.idmenu = nivel_menu.idmenu');
        $this->db->where('nivel_menu.idnivel', $idnivel);
        $this->db->where('menu2.estado', 'Activo');
        $this->db->where('menu2.padre_id', $idmenu);
        return $this->db->get()->result_array();
    }

    public function get_all_menus() {
        return $this->db->get('menu2')->result_array();
    }

    public function get_menu_by_id($id) {
        return $this->db->get_where('menu2', ['idmenu' => $id])->row_array();
    }

    public function insert_menu($data) {
        return $this->db->insert('menu2', $data);
    }

    public function update_menu($data) {
        return $this->db->update('menu2', $data, ['idmenu' => $data['idmenu']]);
    }

    public function delete_menu($id) {
        return $this->db->delete('menu2', ['idmenu' => $id]);
    }
}
