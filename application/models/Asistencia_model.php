<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asistencia_model extends CI_Model {

    public function obtener_asistencia($empleado_id, $fecha) {
        $this->db->where('empleado_id', $empleado_id);
        $this->db->where('fecha', $fecha);
        return $this->db->get('asistencia')->row_array();
    }

    public function insertar_asistencia($datos) {
        $this->db->insert('asistencia', $datos);
        return $this->db->insert_id();
    }

    public function actualizar_asistencia($asistencia_id, $datos) {
        $this->db->where('asistencia_id', $asistencia_id);
        $this->db->update('asistencia', $datos);
    }
}
?>
