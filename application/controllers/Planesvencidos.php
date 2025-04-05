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
    
        // Parámetros para el query
        $params = [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'];
    
        // Armamos filtros dinámicos
        $filtros = "";
    
        if (!empty($sede)) {
            if (is_array($sede)) {
                // Genera placeholders (?, ?, ?) para cada sede
                $placeholders = implode(',', array_fill(0, count($sede), '?'));
                $filtros .= " AND se.nombre IN ($placeholders)";
                $params = array_merge($params, $sede);
            } else {
                $filtros .= " AND se.nombre = ?";
                $params[] = $sede;
            }
        }
    
        if (!empty($especie)) {
            $filtros .= " AND ma.especie = ?";
            $params[] = $especie;
        }
    
        // Consulta SQL
        $sql = "
            WITH Planes AS (
                SELECT
                    mascota_patient_id,
                    tenant_id,
                    MAX(fecha_fin) AS ultima_fecha_fin
                FROM planessalud2
                WHERE is_deleted = 0
                GROUP BY mascota_patient_id, tenant_id
            ),
            DireccionUnica AS (
                SELECT
                    cd.cliente_id,
                    MAX(cd.direccion_id) AS direccion_id
                FROM clientes_direcciones cd
                GROUP BY cd.cliente_id
            )
            SELECT
                ma.fecha_actualizacion,
                se.nombre AS sede,
                CONCAT(ma.apellido, ' ', ma.nombre) AS mascota,
                c.identificacion AS IdentificacionAmo,
                CONCAT(c.apellido, ' ', c.nombre) AS cliente,
                TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE()) AS Edad,
                ma.sexo, ma.especie, ma.raza,
                ma.fecha_nacimiento,
                CASE
                    WHEN ma.fallecido = 1 THEN 'Fallecido'
                    ELSE 'Activo'
                END AS EstatusMascota,
                CASE
                    WHEN p.ultima_fecha_fin IS NULL THEN 'Sin Plan'
                    WHEN p.ultima_fecha_fin >= CURDATE() THEN 'Vigente'
                    ELSE 'Vencido'
                END AS EstadoPlan,
                p.ultima_fecha_fin AS fecha_fin,
                CASE
                    WHEN p.ultima_fecha_fin IS NULL THEN NULL
                    ELSE DATEDIFF(p.ultima_fecha_fin, CURDATE())
                END AS dias_vencidos,
                c.movil AS movil,
                c.email AS email,
                d.calle1 AS Direccion,
                d.calle2 AS Distrito,
                d.ubigeo,
                d.address_id AS AddressId,
                c.patient_id AS ClienteId,
                ma.patient_id AS MascotaId
            FROM mascotas2 ma
            LEFT JOIN Planes p
                ON p.mascota_patient_id = ma.patient_id
                AND p.tenant_id = ma.tenant_id
            LEFT JOIN sedes se
                ON ma.tenant_id = se.TenantId
            LEFT JOIN clientes_mascotas cm
                ON cm.mascota_id = ma.patient_id
            LEFT JOIN clientes2 c
                ON c.patient_id = cm.cliente_id
            LEFT JOIN DireccionUnica du
                ON du.cliente_id = c.patient_id
            LEFT JOIN direcciones2 d
                ON d.address_id = du.direccion_id
            WHERE p.ultima_fecha_fin BETWEEN ? AND ?
            $filtros
            GROUP BY ma.patient_id, c.patient_id
            ORDER BY p.ultima_fecha_fin DESC
        ";
    
        $resultado = $this->db->query($sql, $params)->result_array();
    
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }    

	
    
    public function obtener_planes_old() {
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