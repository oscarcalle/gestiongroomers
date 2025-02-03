<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meta_model extends CI_Model {

    public function get_all() {
        $this->db->select('metas.idmeta, metas.idsede, metas.mes, metas.anio, metas.meta, sedes.nombre AS sede_nombre');
        $this->db->from('metas');
        $this->db->join('sedes', 'metas.idsede = sedes.TenantId');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert($data) {
        $this->db->insert('metas', $data);
        return $this->db->insert_id();
    }

    public function update($idmeta, $data) {
        $this->db->where('idmeta', $idmeta);
        $this->db->update('metas', $data);
    }

    public function delete($idmeta) {
        $this->db->where('idmeta', $idmeta);
        $this->db->delete('metas');
    }

    public function get($idmeta) {
        $this->db->where('idmeta', $idmeta);
        $query = $this->db->get('metas');
        return $query->row_array();
    }

    public function exists($idsede, $mes, $anio) {
        $this->db->where('idsede', $idsede);
        $this->db->where('mes', $mes);
        $this->db->where('anio', $anio);
        $query = $this->db->get('metas');
        return $query->num_rows() > 0;
    }
}
