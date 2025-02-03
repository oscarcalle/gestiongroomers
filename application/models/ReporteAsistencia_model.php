<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReporteAsistencia_model extends CI_Model {

    public function obtener_asistencia_rango($fecha_inicio, $fecha_fin) {
        $this->db->select('e.empleado_id, CONCAT(e.apellido, " ", e.nombre) AS nombre_completo, 
                           a.fecha, a.estado');
        $this->db->from('empleados e');
    
        // Construir la cláusula ON con fechas bien escapadas
        $on_clause = 'e.empleado_id = a.empleado_id AND a.fecha BETWEEN ' . 
                     $this->db->escape($fecha_inicio) . ' AND ' . $this->db->escape($fecha_fin);
    
        $this->db->join('asistencia a', $on_clause, 'left');
        $this->db->where('e.ocultar_en_reporte', 'no');
        $this->db->order_by('e.apellido, e.nombre');
    
        return $this->db->get()->result_array();
    }           
    
}
?>
