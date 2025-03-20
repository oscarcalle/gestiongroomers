<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard_mascotas extends CI_Controller {

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
        $this->load->view($data['mensaje'] ? 'error_view' : 'dashboard_mascotas', $data);
        $this->load->view('__footer');
    }
    
    public function getData() {
        $requestData = $this->input->post();
    
        if (!$requestData) {
            echo json_encode(["error" => "No se recibió una solicitud válida."]);
            return;
        }
    
        // Parámetros de paginación y orden
        $start = isset($requestData['start']) ? intval($requestData['start']) : 0;
        $length = isset($requestData['length']) ? intval($requestData['length']) : 10;
        $draw = isset($requestData['draw']) ? intval($requestData['draw']) : 0;
        $searchValue = !empty($requestData['search']['value']) ? $this->db->escape_like_str($requestData['search']['value']) : null;
    
        // Mapeo de columnas para búsqueda y ordenamiento
        $columnMap = [
            'fecha_actualizacion' => 'ma.fecha_actualizacion',
            'Sede' => 'se.nombre',
            'Mascota' => "CONCAT(ma.apellido, ' ', ma.nombre)",
            'IdentificacionAmo' => 'c.identificacion',
            'Amo' => "CONCAT(c.apellido, ' ', c.nombre)",
            'Edad' => 'TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE())',
            'sexo' => 'ma.sexo',
            'especie' => 'ma.especie',
            'raza' => 'ma.raza',
            'fecha_nacimiento' => 'ma.fecha_nacimiento',
            'EstatusMascota' => "CASE WHEN ma.fallecido = 1 THEN 'Fallecido' ELSE 'Activo' END",
            'ImporteAcumulado' => 'ia.ImporteAcumulado',
            'UltimaVentaMascota' => 'uv.UltimaVentaMascota',
            'TiempoTranscurridoUltimaCompra' => "CASE WHEN uv.UltimaVentaMascota IS NULL THEN NULL ELSE DATEDIFF(CURDATE(), uv.UltimaVentaMascota) END",
            'EstadoPlan' => "CASE WHEN p.ultima_fecha_fin IS NULL THEN 'Sin Plan' 
                              WHEN p.ultima_fecha_fin >= CURDATE() THEN 'Vigente' 
                              ELSE 'Vencido' END",
            'FechaFinPlan' => 'p.ultima_fecha_fin',
            'DiferenciaDias' => "DATEDIFF(p.ultima_fecha_fin, CURDATE())",
            'Telefono' => 'c.movil',
            'Email' => 'c.email',
            'Direccion' => 'd.calle1',
            'Distrito' => 'd.calle2',
            'ubigeo' => 'd.ubigeo',
            'AddressId' => 'd.address_id',
            'ClienteId' => 'c.patient_id',
            'MascotaId' => 'ma.patient_id'
        ];
    
        // Ordenamiento
        $orderColumnIndex = isset($requestData['order'][0]['column']) ? intval($requestData['order'][0]['column']) : 0;
        $orderColumnKey = array_keys($columnMap)[$orderColumnIndex] ?? 'fecha_actualizacion';
        $orderColumn = $columnMap[$orderColumnKey] ?? 'ma.fecha_actualizacion';
        $orderDir = isset($requestData['order'][0]['dir']) && in_array($requestData['order'][0]['dir'], ['asc', 'desc'])
            ? $requestData['order'][0]['dir']
            : 'desc';
    
        // Construcción de la consulta SQL
        $sql = "
            WITH Planes AS (
                SELECT mascota_patient_id, tenant_id, MAX(fecha_fin) AS ultima_fecha_fin
                FROM planessalud2 WHERE is_deleted = 0
                GROUP BY mascota_patient_id, tenant_id
            ),
            DireccionUnica AS (
                SELECT cliente_id, MAX(direccion_id) AS direccion_id  
                FROM clientes_direcciones GROUP BY cliente_id
            ),
            UltimaVenta AS (
                SELECT MascotaPatientId, TenantId, MAX(FechaDelDocumento) AS UltimaVentaMascota
                FROM sales GROUP BY MascotaPatientId, TenantId
            ),
            ImporteAcumulado AS (
                SELECT MascotaPatientId, TenantId, SUM(GlobalTotal) AS ImporteAcumulado
                FROM sales WHERE Anulado = 0
                GROUP BY MascotaPatientId, TenantId
            )
            SELECT SQL_CALC_FOUND_ROWS 
                ma.fecha_actualizacion AS fecha_actualizacion,
                se.nombre AS Sede,
                CONCAT(ma.apellido, ' ', ma.nombre) AS Mascota,
                c.identificacion AS IdentificacionAmo,
                CONCAT(c.apellido, ' ', c.nombre) AS Amo,
                TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE()) AS Edad,
                ma.sexo AS sexo, 
                ma.especie AS especie, 
                ma.raza AS raza, 
                ma.fecha_nacimiento AS fecha_nacimiento,
                CASE WHEN ma.fallecido = 1 THEN 'Fallecido' ELSE 'Activo' END AS EstatusMascota,
                ia.ImporteAcumulado AS ImporteAcumulado,
                uv.UltimaVentaMascota AS UltimaVentaMascota,
                CASE WHEN uv.UltimaVentaMascota IS NULL THEN NULL 
                     ELSE DATEDIFF(CURDATE(), uv.UltimaVentaMascota) END AS TiempoTranscurridoUltimaCompra,
                CASE WHEN p.ultima_fecha_fin IS NULL THEN 'Sin Plan' 
                     WHEN p.ultima_fecha_fin >= CURDATE() THEN 'Vigente' 
                     ELSE 'Vencido' END AS EstadoPlan,
                p.ultima_fecha_fin AS FechaFinPlan,
                DATEDIFF(p.ultima_fecha_fin, CURDATE()) AS DiferenciaDias,
                c.movil AS Telefono,
                c.email AS Email,
                d.calle1 AS Direccion,
                d.calle2 AS Distrito,
                d.ubigeo AS ubigeo, 
                d.address_id AS AddressId,
                c.patient_id AS ClienteId,
                ma.patient_id AS MascotaId
            FROM mascotas2 ma
            LEFT JOIN Planes p ON p.mascota_patient_id = ma.patient_id AND p.tenant_id = ma.tenant_id
            LEFT JOIN sedes se ON ma.tenant_id = se.TenantId
            LEFT JOIN clientes_mascotas cm ON cm.mascota_id = ma.patient_id
            LEFT JOIN clientes2 c ON c.patient_id = cm.cliente_id
            LEFT JOIN DireccionUnica du ON du.cliente_id = c.patient_id
            LEFT JOIN direcciones2 d ON d.address_id = du.direccion_id
            LEFT JOIN UltimaVenta uv ON uv.MascotaPatientId = ma.patient_id AND uv.TenantId = ma.tenant_id
            LEFT JOIN ImporteAcumulado ia ON ia.MascotaPatientId = ma.patient_id AND ia.TenantId = ma.tenant_id
            WHERE TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE()) BETWEEN 0 AND 15
        ";
    
        // Búsqueda global
        if ($searchValue) {
            $sql .= " AND (" . implode(" LIKE '%$searchValue%' OR ", $columnMap) . " LIKE '%$searchValue%')";
        }
    

        if (!empty($requestData['columnSearch'])) {
            foreach ($requestData['columnSearch'] as $columnKey => $searchValue) {
                if (!empty($searchValue) && isset($columnMap[$columnKey])) {
                    $searchColumnValue = $this->db->escape_like_str($searchValue);
                    $sql .= " AND {$columnMap[$columnKey]} LIKE '%$searchColumnValue%'";
                }
            }
        }        

        // Filtrar por sede
        $sede = isset($requestData['sede']) ? $requestData['sede'] : '';
        if (!empty($sede)) {
            if (is_array($sede)) {
                $sedeList = implode("','", array_map([$this->db, 'escape_str'], $sede));
                $sql .= " AND ma.tenant_id IN ('$sedeList')";
            } else {
                $sede = $this->db->escape_str($sede);
                $sql .= " AND ma.tenant_id = '$sede'";
            }
        }

        // Filtrar por rango de fechas
        $fechaInicio = isset($requestData['inicio']) ? $requestData['inicio'] : '';
        $fechaFin = isset($requestData['fin']) ? $requestData['fin'] : '';
        $fechaInicioSQL = date('Y-m-d', strtotime($fechaInicio)) . ' 00:00:00';
        $fechaFinSQL = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
        
                $sql .= " AND ma.fecha_actualizacion BETWEEN '$fechaInicioSQL' AND '$fechaFinSQL'";
        
        


        // Orden y paginación
        $sql .= " GROUP BY ma.patient_id, c.patient_id";
        $sql .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    
        // Ejecutar consulta
        $query = $this->db->query($sql);
        $data = $query->result_array();
        $totalFiltered = $this->db->query('SELECT FOUND_ROWS() AS count')->row()->count;
    
        // Enviar respuesta JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $totalFiltered,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ], JSON_UNESCAPED_UNICODE);
    }

    public function top_mascotas() {
        $request = json_decode(file_get_contents('php://input'), true); 
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";

        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        
        $sql = "
        SELECT 
            CONCAT(ma.apellido, ' ', ma.nombre) AS Mascota,
            se.nombre AS Sede, 
            ma.sexo, ma.especie,
            COUNT(s.SaleId) AS Ventas,
            SUM(s.GlobalTotal) AS Total
        FROM (
            SELECT SaleId, TenantId, MascotaPatientId, GlobalTotal
            FROM sales
            WHERE FechaDelDocumento >= '2023-01-01 00:00:00'
                AND Anulado = 0
        ) s
        LEFT JOIN sedes se ON s.TenantId = se.TenantId
        LEFT JOIN mascotas2 ma ON s.MascotaPatientId = ma.patient_id AND ma.tenant_id = s.TenantId
        WHERE ma.fecha_actualizacion BETWEEN ? AND ?  and
        ma.fallecido = 0 AND TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE()) BETWEEN 1 AND 15
        $sedeClause
        GROUP BY se.nombre, s.MascotaPatientId, ma.apellido, ma.nombre
        ORDER BY Total DESC
        LIMIT 10;
        ";
    
        // Ejecutar la consulta con los parámetros de fecha
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        
        // Devolver el resultado en formato JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function piramide_mascotas() {
        // Obtener el parámetro 'sede' de la URL
        $sede = $this->input->get('sede');

        $fecha_inicio = $this->input->get('start');
        $fecha_fin = $this->input->get('end');
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Consulta base
        $sql = "
            SELECT TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad,
                especie,
                sexo,
                COUNT(*) AS cantidad
            FROM mascotas2
            WHERE fecha_actualizacion BETWEEN ? AND ?  and
            FALLECIDO = 0 AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 1 AND 15

        ";
    
        // Filtrar por sede si se proporciona
        if (!empty($sede)) {
            $sede = $this->db->escape_str($sede);
            $sql .= " AND tenant_id in ($sede)";
        }
        
    
        $sql .= " GROUP BY edad, especie, sexo ORDER BY edad DESC";
    
        // Ejecutar la consulta
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
    
        // Devolver el resultado en formato JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function direcciones_mascotas() {
        // Obtener el parámetro 'sede' de la URL
        $sede = $this->input->get('sede');

        $fecha_inicio = $this->input->get('start');
        $fecha_fin = $this->input->get('end');
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Consulta base
        $sql = "
            WITH DireccionUnica AS (
                SELECT cliente_id, MAX(direccion_id) AS direccion_id  
                FROM clientes_direcciones 
                GROUP BY cliente_id
            ),
            CantidadMascotas AS (
                SELECT cm.cliente_id, COUNT(cm.mascota_id) AS CantidadMascotas
                FROM clientes_mascotas cm
                GROUP BY cm.cliente_id
            )
            SELECT 
                se.nombre AS sede,
                CONCAT(UCASE(LEFT(d.calle2, 1)), LCASE(SUBSTRING(d.calle2, 2))) AS distrito,
                d.ubigeo AS Ubigeo,
                SUM(cm.CantidadMascotas) AS total
            FROM clientes2 c
            LEFT JOIN DireccionUnica du ON du.cliente_id = c.patient_id
            LEFT JOIN direcciones2 d ON d.address_id = du.direccion_id
            LEFT JOIN CantidadMascotas cm ON cm.cliente_id = c.patient_id
            LEFT JOIN sedes se ON c.tenant_id = se.TenantId
            WHERE c.fecha_actualizacion BETWEEN ? AND ?  and
            d.calle2 IS NOT NULL 
            AND d.calle2 <> ''
            AND d.ubigeo REGEXP '^[0-9]+$'
            AND cm.CantidadMascotas > 0
            AND (d.ubigeo LIKE '1501%' OR d.ubigeo LIKE '0701%')
        ";
    
        // Filtrar por sede si se proporciona
        if (!empty($sede)) {
            $sede = $this->db->escape_str($sede);
            $sql .= " AND c.tenant_id IN ($sede)";
        }
    
        // Agrupar los resultados
        $sql .= " GROUP BY se.nombre, d.calle2, d.ubigeo";
    
        // Ordenar los distritos en el orden específico primero, luego el resto alfabéticamente
        $sql .= " ORDER BY 
            FIELD(calle2, 
                'Surquillo', 'Santiago de Surco', 'San Miguel', 'San Luis', 'San Isidro', 
                'San Borja', 'Miraflores', 'Pueblo Libre', 'Magdalena del Mar', 'Lince', 
                'La Victoria', 'La Molina', 'Jesus Maria', 'Chorrillos', 'Barranco', 'Lima'
            )
        DESC, calle2 ASC";
    
        // Ejecutar la consulta
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
    
        // Devolver el resultado en formato JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }
    
    

    // Función para construir la cláusula de sede
    private function build_in_clause($column, $values) {
        // Si no se seleccionan valores o si se incluyen todas las sedes (en este caso, 6 sedes), no agregar la condición
        if (empty($values) || count($values) === 6) return '';
    
        // Escapar los valores y crear la cláusula IN
        $escaped_values = implode(',', array_map([$this->db, 'escape'], $values));
        return "AND $column IN ($escaped_values)";
    }

    private function output_error($message) {
        echo json_encode(['error' => $message]);
        exit;
    }
    
    public function descargarExcel() { 
        require 'vendor/autoload.php'; // Cargar PhpSpreadsheet
    
        // Leer el contenido del request
        $request = json_decode(file_get_contents('php://input'), true);
    
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Construcción de la consulta SQL (sin paginación)
        $sql = "
            WITH Planes AS (
                SELECT mascota_patient_id, tenant_id, MAX(fecha_fin) AS ultima_fecha_fin
                FROM planessalud2 WHERE is_deleted = 0
                GROUP BY mascota_patient_id, tenant_id
            ),
            DireccionUnica AS (
                SELECT cliente_id, MAX(direccion_id) AS direccion_id  
                FROM clientes_direcciones GROUP BY cliente_id
            ),
            UltimaVenta AS (
                SELECT MascotaPatientId, TenantId, MAX(FechaDelDocumento) AS UltimaVentaMascota
                FROM sales GROUP BY MascotaPatientId, TenantId
            ),
            ImporteAcumulado AS (
                SELECT MascotaPatientId, TenantId, SUM(GlobalTotal) AS ImporteAcumulado
                FROM sales WHERE Anulado = 0
                GROUP BY MascotaPatientId, TenantId
            )
            SELECT 
                ma.fecha_actualizacion AS fecha_actualizacion,
                se.nombre AS Sede,
                CONCAT(ma.apellido, ' ', ma.nombre) AS Mascota,
                c.identificacion AS IdentificacionAmo,
                CONCAT(c.apellido, ' ', c.nombre) AS Amo,
                TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE()) AS Edad,
                ma.sexo AS sexo, 
                ma.especie AS especie, 
                ma.raza AS raza, 
                ma.fecha_nacimiento AS fecha_nacimiento,
                CASE WHEN ma.fallecido = 1 THEN 'Fallecido' ELSE 'Activo' END AS EstatusMascota,
                ia.ImporteAcumulado AS ImporteAcumulado,
                uv.UltimaVentaMascota AS UltimaVentaMascota,
                CASE WHEN uv.UltimaVentaMascota IS NULL THEN NULL 
                     ELSE DATEDIFF(CURDATE(), uv.UltimaVentaMascota) END AS TiempoTranscurridoUltimaCompra,
                CASE WHEN p.ultima_fecha_fin IS NULL THEN 'Sin Plan' 
                     WHEN p.ultima_fecha_fin >= CURDATE() THEN 'Vigente' 
                     ELSE 'Vencido' END AS EstadoPlan,
                p.ultima_fecha_fin AS FechaFinPlan,
                DATEDIFF(p.ultima_fecha_fin, CURDATE()) AS DiferenciaDias,
                c.movil AS Telefono,
                c.email AS Email,
                d.calle1 AS Direccion,
                d.calle2 AS Distrito,
                d.ubigeo AS ubigeo, 
                d.address_id AS AddressId,
                c.patient_id AS ClienteId,
                ma.patient_id AS MascotaId
            FROM mascotas2 ma
            LEFT JOIN Planes p ON p.mascota_patient_id = ma.patient_id AND p.tenant_id = ma.tenant_id
            LEFT JOIN sedes se ON ma.tenant_id = se.TenantId
            LEFT JOIN clientes_mascotas cm ON cm.mascota_id = ma.patient_id
            LEFT JOIN clientes2 c ON c.patient_id = cm.cliente_id
            LEFT JOIN DireccionUnica du ON du.cliente_id = c.patient_id
            LEFT JOIN direcciones2 d ON d.address_id = du.direccion_id
            LEFT JOIN UltimaVenta uv ON uv.MascotaPatientId = ma.patient_id AND uv.TenantId = ma.tenant_id
            LEFT JOIN ImporteAcumulado ia ON ia.MascotaPatientId = ma.patient_id AND ia.TenantId = ma.tenant_id
            WHERE ma.fecha_actualizacion BETWEEN ? AND ?  
            AND TIMESTAMPDIFF(YEAR, ma.fecha_nacimiento, CURDATE()) BETWEEN 0 AND 15
        ";
    
        // Filtrar por sede si se proporciona
        $params = [$fecha_inicio, $fecha_fin];
    
        if (!empty($request['sedes'])) {
            $placeholders = implode(',', array_fill(0, count($request['sedes']), '?'));
            $sql .= " AND ma.tenant_id IN ($placeholders)";
            $params = array_merge($params, $request['sedes']);
        }
    
        $sql .= " GROUP BY ma.patient_id, c.patient_id";
        $sql .= " ORDER BY ma.fecha_actualizacion DESC";
    
        // Ejecutar consulta correctamente con parámetros
        $query = $this->db->query($sql, $params);
        $resultado = $query->result_array();
    
        if (empty($resultado)) {
            return $this->output_error('No se encontraron datos');
        }
    
        // Generar el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte');
    
        // Definir los encabezados
        $headers = array_keys($resultado[0]);
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column++ . '1', mb_convert_encoding($header, 'UTF-8', 'auto'));
        }
    
        // Llenar los datos
        $rowNumber = 2;
        foreach ($resultado as $row) {
            $column = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($column++ . $rowNumber, mb_convert_encoding($value ?: 'N/A', 'UTF-8', 'auto'));
            }
            $rowNumber++;
        }
    
        // Configurar cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        try {
            $writer->save('php://output');
        } catch (\Exception $e) {
            log_message('error', 'Error al generar el archivo Excel: ' . $e->getMessage());
            return $this->output_error('Ocurrió un error al generar el archivo Excel');
        }
    
        exit();
    }
    
}
?>