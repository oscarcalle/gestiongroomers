<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function get_all() {
        return $this->db->get('usuario')->result_array();
    }

    public function get_all_users() {
        $this->db->select('usuario.*, nivel.nombre AS nivel_nombre');
        $this->db->from('usuario');
        $this->db->join('nivel', 'nivel.idnivel = usuario.idnivel', 'left');
        $this->db->order_by('nivel.idnivel', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_usuario($id) {
        $this->db->where('idusuario', $id);
        $query = $this->db->get('usuario');
        return $query->row_array(); // Esto debe incluir el campo 'sedes'
    }    

    public function get_by_id($id) {
        return $this->db->get_where('usuario', ['idusuario' => $id])->row_array();
    }

    public function insert($data) {
        return $this->db->insert('usuario', $data);
    }

    public function update($id, $data) {
        $this->db->where('idusuario', $id);
        return $this->db->update('usuario', $data);
    }

    public function delete($id) {
        $this->db->where('idusuario', $id);
        return $this->db->delete('usuario');
    }

    public function get_niveles() {
        return $this->db->get('nivel')->result_array();
    }
    
    public function get_sedes() {
        return $this->db->get('sedes')->result_array();
    }
    
}
