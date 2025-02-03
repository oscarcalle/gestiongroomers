<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database(); // Cargamos la base de datos aquí
    }

    function index() {
        $this->load->view('login');
    }
 
	function masuk() {
		$username = $this->input->get('username');
		$password = $this->input->get('password');
	
		// Validamos los datos
		if (empty($username) || empty($password)) {
			echo json_encode(['success' => false, 'message' => 'Por favor ingrese ambos campos.']);
			return;
		}
	
		// Realizamos la consulta con INNER JOIN usando Query Builder
		$this->db->select('u.*, n.nombre AS nivel');
		$this->db->from('usuario u');
		$this->db->join('nivel n', 'u.idnivel = n.idnivel');
		$this->db->where('u.user', $username);
		$this->db->where('u.clave', $password); // Considera usar md5 si es necesario
	
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			$user = $query->row();
	
			// Almacenamos los datos de sesión
			$this->session->set_userdata([
				'username' => $user->user,
				'role' => $user->nivel,
				'idnivel' => $user->idnivel,
				'idusuario' => $user->idusuario,
				'email' => $user->email,
				'sedes' => $user->sedes,
				'status' => "AezakmiHesoyamWhosyourdaddy"
			]);
	
			// Redirigimos según el idnivel
			//$redirect_url = ($user->idnivel == 8) ? base_url('dashboard') : base_url('inicio');
			$redirect_url = '';

			switch ($user->idnivel) {
				case 1:
					$redirect_url = base_url('dashboard'); //Administrador
					break;
				case 2:
					$redirect_url = base_url('inicio'); //Counter
					break;
				case 3:
					$redirect_url = base_url('dashboard2'); //Administrador de agencia
					break;
				case 4:
					$redirect_url = base_url('inicio'); //Counter principal
					break;
				case 5:
					$redirect_url = base_url('inicio'); //Gerente de operaciones
					break;
				case 6:
					$redirect_url = base_url('inicio'); //Gerente area medica
					break;
				case 7:
					$redirect_url = base_url('inicio'); //Jefe de operaciones
					break;
				case 8:
					$redirect_url = base_url('inicio'); //Auditoria
					break;
				case 9:
					$redirect_url = base_url('dashboard'); //Gerente general
					break;
				case 10:
					$redirect_url = base_url('inicio'); //Jefe de auditoria
					break;
				default:
					$redirect_url = base_url('inicio'); // Redirección predeterminada
					break;
			}

			echo json_encode([
				'success' => true,
				'redirect' => $redirect_url
			]);

	
		} else {
			echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
		}
	}
	
	

    function logout() {
        $this->session->sess_destroy();
        redirect(base_url('Login'));
    }
}
