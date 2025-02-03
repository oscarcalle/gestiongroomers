<?php
class PlanesVencidos_model extends CI_Model {

public function __construct() {
    parent::__construct();
}


public function guardar_contacto($data) {
    $this->db->where('plan_salud_id', $data['plan_salud_id']);
    $this->db->update('planes_contacto_cache', [
        'contactado' => $data['contactado'],
        'renovo' => $data['renovo'],
        'responsable_contacto' => $data['responsable_contacto'],
        'motivo' => $data['motivo'],
        'motivo_otro' => $data['motivo_otro'] ?? null,
        'fecha_contacto' => date('Y-m-d H:i:s')
    ]);
}
}
