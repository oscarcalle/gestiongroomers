<?php 

class Mascotas extends CI_Controller {

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
        $this->load->view($data['mensaje'] ? 'error_view' : 'mascotas', $data);
        $this->load->view('__footer');
    } 

    public function getData() {
        // Obtener datos de la solicitud POST
        $requestData = $this->input->post();
        
        // Definir columnas
        $columns = array(
            'patient_id' => 'p.patient_id',
            'nombre' => 'p.nombre',
            'apellido' => 'p.apellido',
            'identificacion' => 'p.identificacion',
            'fallecido' => 'p.fallecido',
            'fecha_nacimiento' => 'p.fecha_nacimiento',
            'sexo' => 'p.sexo',
            'especie' => 'p.especie',
            'raza' => 'p.raza',
            'amo' => 'amo',
            'sede' => 's.nombre',
            'fecha_actualizacion' => 'p.fecha_actualizacion',
        );

        $this->db->select('SQL_CALC_FOUND_ROWS p.patient_id, p.nombre, p.apellido, p.identificacion, p.fallecido, p.fecha_nacimiento, p.sexo, p.especie, p.raza, CONCAT(c.apellido, " ", c.nombre) AS amo, p.fecha_actualizacion, s.nombre AS sede', false);
        $this->db->from('mascotas_cliente mc');
        $this->db->join('mascotas2 p', 'p.patient_id=mc.mascota_id', 'inner');
        $this->db->join('clientes2 c', 'mc.patient_id=c.patient_id', 'left');
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
        $subquery = "(SELECT identificacion FROM mascotas2 WHERE identificacion != '' GROUP BY identificacion HAVING COUNT(identificacion) > 1)";
        switch ($tipoConsulta) {
            case 'duplicados':
                $this->db->where("p.identificacion IN $subquery", null, false);
                break;
            case 'unicos':
                $this->db->where("p.identificacion NOT IN $subquery", null, false);
                break;
            case 'nombre_empieza_con':
                $this->db->where('p.nombre LIKE', 'fam%');
                break;
            case 'sin_nombre':
                $this->db->where('p.identificacion IS NOT NULL');
                $this->db->where('p.nombre', '');
                break;
            case 'sin_identificacion':
                $this->db->where('p.identificacion', '');
                $this->db->where('p.nombre IS NOT NULL');
                break;
            case 'numeros_en_nombre':
                $this->db->where('p.nombre REGEXP', '[0-9]+');
                break;
            case 'numeros_en_apellidos':
                $this->db->where('p.apellido REGEXP "[0-9]+"'); // Buscar nombres con números
                //$this->db->where('p.apellido NOT REGEXP "[\'\"’ʼ]"'); // Excluir diferentes tipos de apóstrofos y comillas
                break;
            case 'contiene_no_atender':
                $this->db->where('(p.nombre LIKE "%ATENDER%" OR p.nombre LIKE "%USAR%")', null, false);
            break;
            case 'nombre_corto':
                $this->db->where('CHAR_LENGTH(p.nombre) < 4 AND p.nombre NOT LIKE "fam%"', null, false);
                break;
            case 'nombre_largo':
                $this->db->where('CHAR_LENGTH(p.nombre) > 20');
                break;
            // Otros casos omitidos por brevedad
            
        }
    
        // Filtrar por rango de fechas
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
        $this->db->group_by('mc.tenant_id, mc.patient_id, mc.mascota_id');
    
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