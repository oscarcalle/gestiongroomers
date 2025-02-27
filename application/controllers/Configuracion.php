<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database(); // Cargar la base de datos
    }

    public function obtener_background() {
        // Obtener el tipo de fondo (imagen o default)
        $queryTipo = $this->db->get_where('digide', ['clave' => 'background_type']);
        $tipoFondo = $queryTipo->row() ? $queryTipo->row()->valor : 'default';

        // Si el tipo es 'imagen', obtener la URL de la imagen
        $urlImagen = '';
        if ($tipoFondo === 'imagen') {
            $queryImagen = $this->db->get_where('digide', ['clave' => 'background_image']);
            $urlImagen = $queryImagen->row() ? $queryImagen->row()->valor : '';
        }

        echo json_encode([
            'success' => true,
            'background_type' => $tipoFondo,
            'background' => $urlImagen
        ]);
    }
}
