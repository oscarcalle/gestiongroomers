<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nivel_model extends CI_Model {

    public function get_all_levels() {
        $this->db->select('*');
        $this->db->from('nivel');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Puedes agregar otros métodos según sea necesario
}
