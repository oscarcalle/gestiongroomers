<?php
class Sinplan_model extends CI_Model {

public function __construct() {
    parent::__construct();
}


public function guardar_contacto($data) {
    $this->db->where('id', $data['id']);
    $this->db->update('mascotas_sin_plan_salud', [
        'contactado' => $data['contactado'],
        'renovo' => $data['renovo'],
        'responsable_contacto' => $data['responsable_contacto'],
        'motivo' => $data['motivo'],
        'motivo_otro' => $data['motivo_otro'] ?? null,
        'fecha_contacto' => date('Y-m-d H:i:s')
    ]);
}
}
