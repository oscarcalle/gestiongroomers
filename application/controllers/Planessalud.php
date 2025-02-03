<?php 

class Planessalud extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        
        if ($this->session->userdata('status') !== "AezakmiHesoyamWhosyourdaddy") {
            redirect(base_url("Login"));
        }
    }

    public function index() {
        $idnivel_usuario = $this->session->userdata('idnivel');
        $sedes_usuario = $this->session->userdata('sedes');
        $ruta = './' . $this->uri->uri_string();
        $data['menu'] = $this->Menu_model->cargar_menu($idnivel_usuario);
        $data['mensaje'] = $this->AsignarMenu_model->tiene_privilegio($idnivel_usuario, $ruta) ? "" : "Usted no tiene privilegios para ver esta página.";
        // Asegúrate de que $sedes_usuario sea un array
        if (!is_array($sedes_usuario)) {
            if (!empty($sedes_usuario) && is_string($sedes_usuario)) {
                $sedes_usuario = explode(',', $sedes_usuario); // Convierte en array si es una cadena separada por comas
            } else {
                $sedes_usuario = []; // Si es null u otro tipo, inicializa como un array vacío
            }
        }
        // Construcción de la consulta
        $this->db->where('estado', 'Habilitado');

        // Si el nivel de usuario no es 1, agrega el filtro TenantId
        if ($idnivel_usuario != 1) {
            $this->db->where_in('TenantId', $sedes_usuario);
        }

        $data['sedes'] = $this->db->get('sedes')->result_array();

        $this->load->view('__header', $data);
        $this->load->view($data['mensaje'] ? 'error_view' : 'planessalud', $data);
        $this->load->view('__footer');
    } 

    public function getData() {
        // Obtener datos de la solicitud POST
        $requestData = $this->input->post();
        
        // Definir columnas
        $columns = array(
            'plan_salud_id' => 'p.plan_salud_id',
            'fecha_inicio' => 'p.fecha_inicio',
            'fecha_fin' => 'p.fecha_fin',
            'no_contrato' => 'p.no_contrato',
            'mascota' => 'mascota',
            'estado' => 'estado',
            'sede' => 's.nombre',
            'fecha_actualizacion' => 'p.fecha_actualizacion',
        );

        $this->db->select('SQL_CALC_FOUND_ROWS p.plan_salud_id, p.fecha_inicio, p.fecha_fin, p.no_contrato, CONCAT(m.apellido, " ", m.nombre) as mascota, 
        IF(p.fecha_fin >= CURDATE(), "Vigente", "Vencido") as estado, 
        p.fecha_actualizacion, s.nombre AS sede', false);
        $this->db->from('planessalud2 p');
        $this->db->join('mascotas2 m', 'p.mascota_patient_id=m.patient_id', 'inner'); 
        $this->db->join('sedes s', 'p.tenant_id = s.TenantId', 'inner');
        
        // Filtro global (general)
        $searchValue = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';
        if (!empty($searchValue)) {
            // Si hay un valor de búsqueda, lo aplicamos a todas las columnas
            $this->db->group_start(); // Inicia un grupo de condiciones OR
            foreach ($columns as $columnName => $dbField) {
                $this->db->or_like($dbField, $searchValue); // Filtrar por cada columna
            }
            $this->db->group_end(); // Cierra el grupo de condiciones OR
        }
    
        // Filtrar por búsqueda de columnas individuales
        foreach ($requestData['columns'] as $columnIndex => $column) {
            $columnSearchValue = isset($column['search']['value']) ? $column['search']['value'] : '';
            if (!empty($columnSearchValue)) {
                // Aplicar filtro solo si hay valor en la columna correspondiente
                $field = isset($columns[$column['data']]) ? $columns[$column['data']] : null;
                if ($field) {
                    $this->db->like($field, $columnSearchValue);
                }
            }
        }
        
        // Filtrar por tipo de consulta
        $tipoConsulta = isset($requestData['tipo_consulta']) ? $requestData['tipo_consulta'] : '';
        switch ($tipoConsulta) {
            case 'vigente':
                $this->db->where('p.fecha_fin >= CURDATE()');
                break;
            case 'vencido':
                $this->db->where('p.fecha_fin < CURDATE()');
                break;
            // Otros casos omitidos por brevedad
            
        }
    
        //Filtrar por rango de fechas
        $fechaInicio = isset($requestData['fecha_inicio']) ? $requestData['fecha_inicio'] : '';
        $fechaFin = isset($requestData['fecha_fin']) ? $requestData['fecha_fin'] : '';
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $fechaInicioSQL = DateTime::createFromFormat('d/m/Y', $fechaInicio)->format('Y-m-d');
            $fechaFinSQL = DateTime::createFromFormat('d/m/Y', $fechaFin)->format('Y-m-d') . ' 23:59:59';
            $this->db->where("p.fecha_actualizacion BETWEEN '$fechaInicioSQL' AND '$fechaFinSQL'", null, false);
        }
    
        // Filtrar por sede
        $sede = isset($requestData['sede']) ? $requestData['sede'] : '';
        if (!empty($sede)) {
            if (is_array($sede)) {
                $this->db->where_in('p.tenant_id', $sede);
            } else {
                $this->db->where('p.tenant_id', $sede);
            }
        }
    
        // Ordenamiento
        $orderColumnIndex = $requestData['order'][0]['column'];
        $orderColumn = array_keys($columns)[$orderColumnIndex];
        $orderDir = $requestData['order'][0]['dir'];
        $this->db->order_by($columns[$orderColumn], $orderDir);
    
        // Paginación
        $start = intval($requestData['start']);
        $length = intval($requestData['length']);
        $this->db->limit($length, $start);

        // Aplicar GROUP BY
        $this->db->group_by('p.plan_salud_id');

        // Obtener resultados y total
        $query = $this->db->get();
        $data = $query->result_array();
        $totalFiltered = $this->db->query('SELECT FOUND_ROWS() AS count')->row()->count;
    
        // Respuesta JSON
        $output = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => $totalFiltered, // Total de registros filtrados
            "recordsFiltered" => $totalFiltered, // Total de registros filtrados
            "data" => $data
        );
    
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($output, JSON_UNESCAPED_UNICODE);

    }
    
}