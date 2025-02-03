<?php 
 
class Liquidaciones extends CI_Controller{
 
	function __construct(){
		parent::__construct();
        $this->load->model('Menu_model'); // Cargar el modelo
        $this->load->model('AsignarMenu_model');
	
		if($this->session->userdata('status') != "AezakmiHesoyamWhosyourdaddy"){
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
            $this->load->view('liquidaciones', $data);
        }
    
        // Cargar el pie de página al final
        $this->load->view('__footer', $data);
    }

    function getUsuarios() {
        $query = $this->db->select('email')
                          ->from('usuario')
                          ->where('email !=', '')
                          ->order_by('email', 'ASC') // Ordenar por email en orden ascendente
                          ->get();
    
        $usuarios = array();
        foreach ($query->result() as $row) {
            $usuarios[] = $row->email;
        }
    
        echo json_encode($usuarios);
    }

	function getSalesData() {
        $fechaInicio = $this->input->get('fechaInicio');
        $fechaFin = $this->input->get('fechaFin');
        $usuario = $this->input->get('usuario');

        $this->db->select('
            sa.SaleId, sa.FechaDelDocumento, sa.UsuarioCreador, sa.NoSerie, 
            sa.NoCorrelativo, sa.PatientId, sa.MascotaPatientId, s.Nombre AS NombreServicio, 
            sd.TotalconIGV, sa.TenantId, sed.nombre AS NombreSede, 
            CONCAT(cli.nombre, " ", cli.apellido) AS Cliente, 
            CONCAT(mas.nombre, " ", mas.apellido) AS Mascota, 
            sa.RUC, sa.RazonSocial, l.importe AS ImportePagado, l.fechaLiquidacion
        ')
        ->from('sale_details sd')
        ->join('servicios s', 'sd.ServicioId = s.ServicioId')
        ->join('sales sa', 'sd.SaleId = sa.SaleId')
        ->join('sedes sed', 'sa.TenantId = sed.TenantId')
        ->join('clientes2 cli', 'sa.PatientId = cli.patient_id', 'left')
        ->join('mascotas2 mas', 'sa.MascotaPatientId = mas.patient_id', 'left')
        ->join('liquidaciones l', 'sa.SaleId = l.SaleId', 'left')
        ->where('s.Nombre IN ("PLAN PROTECCION SALUD PERRO", "PLAN PROTECCION SALUD SENIOR PERRO", "PLAN PROTECCION SALUD GATOS", "PLAN PROTECCION SALUD SENIOR GATOS","RENOVACION PLAN PROTECCION SALUD PERRO", "RENOVACION PLAN PROTECCION SALUD SENIOR PERRO", "RENOVACION PLAN PROTECCION SALUD GATOS", "RENOVACION PLAN PROTECCION SALUD SENIOR GATOS")')
        ->where('sa.Anulado', 0)
        ->where_not_in('sa.TipoDeComprobante', [11, 21])
        ->where('DATE(sa.FechaDelDocumento) >=', $fechaInicio)
        ->where('DATE(sa.FechaDelDocumento) <=', $fechaFin);

        if ($usuario !== 'todos') {
            $this->db->where('sa.UsuarioCreador', $usuario);
        }

        $this->db->group_by('sd.saleChargeId')
                 ->order_by('l.fechaLiquidacion ASC, sa.FechaDelDocumento DESC');

        $query = $this->db->get();
        $data = $query->result_array();

        echo json_encode($data);
    }

	function saveLiquidaciones() {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = ['success' => false, 'boletasPagadas' => []];

        if (!empty($data)) {
            $hayBoletasPagadas = false;

            // Verificar si alguna boleta ya fue pagada
            foreach ($data as $liquidacion) {
                $saleId = $liquidacion['SaleId'];

                $query = $this->db->select('boleta')
                                  ->from('liquidaciones')
                                  ->where('SaleId', $saleId)
                                  ->where('pagado', 1)
                                  ->get();

                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $response['boletasPagadas'][] = $row->boleta;
                    }
                    $hayBoletasPagadas = true;
                }
            }

            // Si hay boletas pagadas, devolvemos respuesta y salimos
            if ($hayBoletasPagadas) {
                echo json_encode($response);
                return;
            }

            // Guardar nuevas liquidaciones
            foreach ($data as $liquidacion) {
                $insertData = [
                    'SaleId' => $liquidacion['SaleId'],
                    'importe' => $liquidacion['importe'],
                    'fechaLiquidacion' => $liquidacion['fechaLiquidacion'],
                    'boleta' => $liquidacion['boleta'],
                    'usuario' => $liquidacion['usuario'],
                    'usuarioRegistrando' => $liquidacion['usuarioRegistrando']
                ];

                if ($this->db->insert('liquidaciones', $insertData)) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    break;
                }
            }
        }

        echo json_encode($response);
    }



}