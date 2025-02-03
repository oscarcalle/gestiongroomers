<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planesvencidos extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('Menu_model'); // Cargar el modelo
		$this->load->model('AsignarMenu_model');
        $this->load->model('Planesvencidos_model');
		
        if ($this->session->userdata('status') != "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
    }

    public function index() {
        // Obtener el nivel del usuario y la ruta actual
        $idnivel_usuario = $this->session->userdata('idnivel');
        $ruta = './' . $this->uri->uri_string();
    
        // Cargar el menú
        $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);
    
        // Verificar acceso a la ruta
        if (!$this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta)) {
            $data['mensaje'] = "Usted no tiene privilegios para ver esta página.";
            $this->load->view('__header', $data);
            $this->load->view('error_view', $data);
        } else {
            $data['mensaje'] = "";
            $this->load->view('__header', $data);
            $this->load->view('planes_vencidos', $data);
        }
    
        // Cargar el pie de página al final
        $this->load->view('__footer', $data);
    }
    
    public function obtener_planes() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		$especie = $this->input->get('especie');
		
		// Iniciar la consulta
		$this->db->select('*')->from('planes_contacto_cache');
		$this->db->where('fecha_fin BETWEEN "' . $fecha_inicio . '" AND "' . $fecha_fin . '" AND estado_renovacion = "No Renovado"');
	
		// Agregar la condición para renovo
		// $this->db->group_start(); // Iniciar un grupo para las condiciones OR
		// $this->db->where('renovo IS NULL');
		// $this->db->or_where('renovo = ""'); // También incluir renovo vacío
		// $this->db->group_end(); // Cerrar el grupo de condiciones
	
		// Comprobar si sede es un array o un solo valor
		if (!empty($sede)) {
			// Si sede es un solo valor, puedes seguir usando where
			// Si sede es un array, usar el IN
			if (is_array($sede)) {
				$this->db->where_in('sede', $sede);
			} else {
				$this->db->where('sede', $sede);
			}
		}

		if(!empty($especie)){
            $this->db->where('especie', $especie);
        }
		
		// Obtener resultados y enviar como JSON
		$sedes = $this->db->get()->result();
		echo json_encode($sedes);
	}
	 
public function obtener_sedes() {
    $sedeusuario = $this->session->userdata('sedes');
    
    // Convertir la cadena en un array si no está vacío
    if (!empty($sedeusuario)) {
        $sedeusuario = explode(',', $sedeusuario);
    }
    
    // Iniciar la consulta
    $this->db->select('nombre as sede')->from('sedes');

    // Verificar si $sedeusuario no está vacío y aplicar el WHERE
    if (!empty($sedeusuario)) {
        $this->db->where_in('TenantId', $sedeusuario);
    }

    // Obtener resultados y enviar como JSON
    $sedes = $this->db->get()->result();
    echo json_encode($sedes);
}

    
	public function obtener_responsables() {
		$idnivel_usuario = $this->session->userdata('idnivel');
		
		if ($idnivel_usuario == 1) {
			// Si el nivel de usuario es 1, se obtienen todos los usuarios ordenados por email
			$responsables = $this->db->select('user, email')
									 ->from('usuario')
									 ->order_by('email', 'ASC')
									 ->get()
									 ->result();
		} else {
			// Si el nivel de usuario no es 1, se obtiene solo el usuario actual, ordenado por email
			$email_usuario = $this->session->userdata('email');
			$responsables = $this->db->select('user, email')
									 ->from('usuario')
									 ->where('email', $email_usuario)
									 ->order_by('email', 'ASC')
									 ->get()
									 ->result();
		}
		
		echo json_encode($responsables);
	}
	
    public function guardar_contacto() {
        // Obtén el cuerpo de la solicitud en formato JSON
        $input = json_decode(file_get_contents('php://input'), true);
    
        // Asegúrate de que recibes correctamente los datos
        $data = [
            'plan_salud_id' => $input['plan_salud_id'],
            'contactado' => $input['contactado'],
            'renovo' => $input['renovo'],
            'responsable_contacto' => $input['responsable_contacto'],
            'motivo' => $input['motivo']
        ];
    
        // Llama a tu modelo para guardar los datos
        $this->Planesvencidos_model->guardar_contacto($data);
        
        echo json_encode(['status' => 'success']);
    }

	public function obtener_motivos() {
		$motivos = $this->db->get('motivos_no_renovacion')->result();
		echo json_encode($motivos);
	}
	
	public function agregar_motivo() {
		$motivo = json_decode(file_get_contents('php://input'), true);
		$this->db->insert('motivos_no_renovacion', ['motivo' => $motivo['motivo']]);
		echo json_encode(['status' => 'success']);
	}
	
	public function actualizar_motivo() {
		$motivo = json_decode(file_get_contents('php://input'), true);
		$this->db->where('id', $motivo['id']);
		$this->db->update('motivos_no_renovacion', ['motivo' => $motivo['motivo']]);
		echo json_encode(['status' => 'success']);
	}
	
	public function eliminar_motivo() {
		$id = json_decode(file_get_contents('php://input'), true);
		$this->db->where('id', $id['id']);
		$this->db->delete('motivos_no_renovacion');
		echo json_encode(['status' => 'success']);
	}
	
	public function obtener_motivo($id) {
		$this->db->where('id', $id);
		$motivo = $this->db->get('motivos_no_renovacion')->row();
		echo json_encode($motivo);
	}
	
	
}