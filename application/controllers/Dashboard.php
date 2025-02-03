<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        
        // if ($this->session->userdata('status') !== "AezakmiHesoyamWhosyourdaddy") {
        //     redirect(base_url("Login"));
        // }
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
        $this->load->view($data['mensaje'] ? 'error_view' : 'dashboard', $data);
        $this->load->view('__footer');
    }

    public function lista_ventas() {
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;

        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }

        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
        
        // Reutilizamos la función para obtener los resultados
        $resultado = $this->obtener_ventas($fecha_inicio, $fecha_fin, $sedeClause, $diaClause, $turnoClause);

        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function oscar() {
        // Leer el contenido del request
        $request = json_decode(file_get_contents('php://input'), true);
    
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
    
        // Query para obtener los datos
        $sql = "
            SELECT 
                se.nombre AS Sede, 
                date(s.FechaDelDocumento) AS FechaVenta,
                time(s.FechaDelDocumento) AS HoraVenta,
                CASE 
                    WHEN s.Anulado = 1 THEN 'Anulada'
                    ELSE 'Emitida'
                END AS Estado,
                s.Descripcion,
                s.GroomersTexto1 AS Clasificacion,
                s.GroomersTexto2 AS SubClasificacion,
                s.GroomersTexto3 AS NumeroOrdenContrato,
                CASE 
                    WHEN s.TipoDeComprobante = 0 THEN 'Boleta Electronica'
                    WHEN s.TipoDeComprobante = 1 THEN 'Factura Electronica'
                    WHEN s.TipoDeComprobante = 2 THEN 'Boleta Fisica'
                    WHEN s.TipoDeComprobante = 3 THEN 'Factura Fisica'
                    WHEN s.TipoDeComprobante = 4 THEN 'Comprobante Interno'
                    WHEN s.TipoDeComprobante = 11 THEN 'Nota de Credito Factura Elect.'
                    WHEN s.TipoDeComprobante = 12 THEN 'Nota de Debito Factura Elect.'
                    WHEN s.TipoDeComprobante = 21 THEN 'Nota de Credito Boleta Elect.'
                    WHEN s.TipoDeComprobante = 22 THEN 'Nota de Debito Boleta Elect.'
                    ELSE 'Borrador'
                END AS TipoComprobante,
                CONCAT(NoSerie, '-', NoCorrelativo) AS NumeroRecibo,
                c.identificacion AS IdentificacionCliente,
                CONCAT(c.apellido, ' ', c.nombre) AS Cliente,
                s.RUC,
                s.RazonSocial,
                CASE
                    WHEN s.Anulado = 1 THEN 0
                    WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalSubTotal
                    ELSE s.GlobalSubTotal
                END AS MontoSinImpuestos,
                CASE
                    WHEN s.Anulado = 1 THEN 0
                    WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalImpuesto
                    ELSE s.GlobalImpuesto
                END AS Impuestos,
                CASE
                    WHEN s.Anulado = 1 THEN 0
                    WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalTotal
                    ELSE s.GlobalTotal
                END AS MontoTotal,
                s.FechaDelDocumento
            FROM sales s 
            LEFT JOIN sedes se ON s.TenantId = se.TenantId
            LEFT JOIN clientes2 c ON s.PatientId = c.patient_id
            WHERE s.FechaDelDocumento BETWEEN ? AND ? 
            $sedeClause 
            $diaClause
            $turnoClause
            group by s.SaleId
            ORDER BY s.FechaDelDocumento DESC";
    
        $resultado = $this->db->query($sql, [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
    
        if (empty($resultado)) {
            return $this->output_error('No se encontraron datos');
        }
    
        // Procesar datos para calcular Turno y Rango
        foreach ($resultado as &$row) {
            $hora = $row['HoraVenta'];
            $horaInt = (int)date('H', strtotime($hora));
            $row['Rango'] = sprintf('%02d:00-%02d:00', $horaInt, ($horaInt + 1) % 24);
    
            if ($hora >= '08:00:00' && $hora < '14:00:00') {
                $row['Turno'] = 'Turno Mañana';
            } else {
                $row['Turno'] = 'Turno Noche';
            }
        }
    
        // Devuelve el resultado en JSON
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }    

    public function descargarExcel() {
        require 'vendor/autoload.php'; // Asegúrate de cargar PhpSpreadsheet

        // Limpiar buffer de salida
        ob_clean();
    
        // Leer el contenido del request
        $request = json_decode(file_get_contents('php://input'), true);
    
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
    
        // Query para obtener los datos
        $sql = "
            SELECT 
                se.nombre AS Sede, 
                date(s.FechaDelDocumento) AS FechaVenta,
                time(s.FechaDelDocumento) AS HoraVenta,
                CASE 
                    WHEN s.Anulado = 1 THEN 'Anulada'
                    ELSE 'Emitida'
                END AS Estado,
                s.Descripcion,
                s.GroomersTexto1 AS Clasificacion,
                s.GroomersTexto2 AS SubClasificacion,
                s.GroomersTexto3 AS NumeroOrdenContrato,
                CASE 
                    WHEN s.TipoDeComprobante = 0 THEN 'Boleta Electronica'
                    WHEN s.TipoDeComprobante = 1 THEN 'Factura Electronica'
                    WHEN s.TipoDeComprobante = 2 THEN 'Boleta Fisica'
                    WHEN s.TipoDeComprobante = 3 THEN 'Factura Fisica'
                    WHEN s.TipoDeComprobante = 4 THEN 'Comprobante Interno'
                    WHEN s.TipoDeComprobante = 11 THEN 'Nota de Credito Factura Elect.'
                    WHEN s.TipoDeComprobante = 12 THEN 'Nota de Debito Factura Elect.'
                    WHEN s.TipoDeComprobante = 21 THEN 'Nota de Credito Boleta Elect.'
                    WHEN s.TipoDeComprobante = 22 THEN 'Nota de Debito Boleta Elect.'
                    ELSE 'Borrador'
                END AS TipoComprobante,
                CONCAT(NoSerie, '-', NoCorrelativo) AS NumeroRecibo,
                c.identificacion AS IdentificacionCliente,
                CONCAT(c.apellido, ' ', c.nombre) AS Cliente,
                s.RUC,
                s.RazonSocial,
                CASE
                    WHEN s.Anulado = 1 THEN 0
                    WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalSubTotal
                    ELSE s.GlobalSubTotal
                END AS MontoSinImpuestos,
                CASE
                    WHEN s.Anulado = 1 THEN 0
                    WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalImpuesto
                    ELSE s.GlobalImpuesto
                END AS Impuestos,
                CASE
                    WHEN s.Anulado = 1 THEN 0
                    WHEN s.TipoDeComprobante IN (11, 21) THEN -s.GlobalTotal
                    ELSE s.GlobalTotal
                END AS MontoTotal,
                s.FechaDelDocumento,
                (SELECT MIN(FechaDelDocumento) 
                FROM sales s2 
                WHERE s2.PatientId = s.PatientId) AS PrimeraCompra
            FROM sales s 
            LEFT JOIN sedes se ON s.TenantId = se.TenantId
            LEFT JOIN clientes2 c ON s.PatientId = c.patient_id
            WHERE s.FechaDelDocumento BETWEEN ? AND ? 
            $sedeClause 
            $diaClause
            $turnoClause
            group by s.SaleId
            ORDER BY s.FechaDelDocumento DESC";
    
        $resultado = $this->db->query($sql, [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
    
        if (empty($resultado)) {
            return $this->output_error('No se encontraron datos');
        }
    
        // Procesar datos para calcular Turno y Rango
        foreach ($resultado as $key => $row) {
            $hora = $row['HoraVenta'];
            $horaInt = (int)date('H', strtotime($hora));
            $resultado[$key]['Rango'] = sprintf('%02d:00-%02d:00', $horaInt, ($horaInt + 1) % 24);
        
            if ($hora >= '08:00:00' && $hora < '14:00:00') {
                $resultado[$key]['Turno'] = 'Turno Mañana';
            } else {
                $resultado[$key]['Turno'] = 'Turno Noche';
            }
        }        
    
        // Generar el archivo Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de Ventas');
    
        // Definir los encabezados
        $headers = array_keys($resultado[0]);
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column++ . '1', $header);
        }
    
        // Llenar los datos
        $rowNumber = 2;
        foreach ($resultado as $row) {
            $column = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($column++ . $rowNumber, $value ?: ' N/A');
            }
            $rowNumber++;
        }
    
        // Configurar cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
        header('Content-Disposition: attachment; filename="reporte_ventas.xlsx"');
        header('Cache-Control: max-age=0');

    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        try {
            $writer->save('php://output');
        } catch (\Exception $e) {
            log_message('error', 'Error al generar el archivo Excel: ' . $e->getMessage());
            return $this->output_error('Ocurrió un error al generar el archivo Excel');
        }
    
        exit();
    }    

    public function descargarExcelVentasDetalladas() {
        require 'vendor/autoload.php';
    
        // Leer y validar fechas
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Construir cláusulas de filtro
        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
    
        // Consulta SQL
        $sql = "
            SELECT 
                se.nombre AS Sede, DATE(s.FechaDelDocumento) AS FechaVenta, TIME(s.FechaDelDocumento) AS HoraVenta,
                CASE WHEN s.Anulado = 1 THEN 'Anulada' ELSE 'Emitida' END AS Estado, 
                s.Descripcion, s.GroomersTexto1 AS Clasificacion, s.GroomersTexto2 AS SubClasificacion, 
                s.GroomersTexto3 AS NumeroOrdenContrato, 
                CASE 
                    WHEN s.TipoDeComprobante = 0 THEN 'Boleta Electronica'
                    WHEN s.TipoDeComprobante = 1 THEN 'Factura Electronica'
                    ELSE 'Otros' 
                END AS TipoComprobante,
                CONCAT(s.NoSerie, '-', s.NoCorrelativo) AS NumeroRecibo, c.identificacion AS Identificacion,
                CONCAT(c.apellido, ' ', c.nombre) AS Cliente, s.RUC, s.RazonSocial,
                COALESCE(NULLIF(sv.Area, ''), NULLIF(p.Area, ''), 'OTROS') AS Area,
                COALESCE(NULLIF(sv.Categoria, ''), NULLIF(p.Categoria, ''), 'OTROS') AS Categoria,
                sd.Cantidad, sd.PrecioUnitario, sv.Nombre AS ServicioNombre, 
                p.Nombre AS ProductoNombre, p.Codigo AS ProductoCodigo, 
                CASE WHEN s.Anulado = 1 THEN 0 ELSE s.GlobalTotal END AS MontoTotal
            FROM sales s 
            LEFT JOIN payments pa ON s.SaleId = pa.SaleId
            LEFT JOIN sedes se ON s.TenantId = se.TenantId
            LEFT JOIN clientes2 c ON s.PatientId = c.patient_id
            LEFT JOIN sale_details sd ON s.SaleId = sd.SaleId
            LEFT JOIN productos p ON sd.ProductoId = p.ProductoId
            LEFT JOIN servicios sv ON sd.ServicioId = sv.ServicioId
            WHERE s.FechaDelDocumento BETWEEN ? AND ?
                  $sedeClause $diaClause $turnoClause
            GROUP BY sd.SaleChargeId
            ORDER BY s.FechaDelDocumento DESC";
        //AND sv.Nombre not IN ('CAFE CLASICO','CAFE CLASICO COLABORADORES','CAFE VARIOS','CAFE VARIOS COLABORADORES')
        // Ejecutar consulta
        $resultado = $this->db->query($sql, ["{$fecha_inicio} 00:00:00", "{$fecha_fin} 23:59:59"])->result_array();
        if (empty($resultado)) {
            return $this->output_error('No se encontraron datos');
        }

        // Procesar datos para calcular Turno y Rango
        foreach ($resultado as $key => $row) {
            $hora = $row['HoraVenta'];
            $horaInt = (int)date('H', strtotime($hora));
            $resultado[$key]['Rango'] = sprintf('%02d:00-%02d:00', $horaInt, ($horaInt + 1) % 24);
        
            if ($hora >= '08:00:00' && $hora < '14:00:00') {
                $resultado[$key]['Turno'] = 'Turno Mañana';
            } else {
                $resultado[$key]['Turno'] = 'Turno Noche';
            }
        }
    
        // Crear archivo Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de Ventas');
    
        // Definir encabezados dinámicamente
        $headers = array_keys($resultado[0]);
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column++ . '1', $header);
        }
    
        // Llenar datos
        $rowNumber = 2;
        foreach ($resultado as $row) {
            $column = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($column++ . $rowNumber, $value);
            }
            $rowNumber++;
        }
    
        // Exportar archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte_ventas_detalladas.xlsx"');
        header('Cache-Control: max-age=0');
    
        try {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            log_message('error', 'Error al generar el archivo Excel: ' . $e->getMessage());
            return $this->output_error('Error al generar el archivo Excel');
        }
    
        exit();
    }    

    // Otras funciones como build_in_clause, build_day_clause y build_turno_clause
    private function output_error($message) {
        $this->output->set_content_type('application/json')->set_output(json_encode(['error' => $message]));
    }

    private function build_in_clause($column, $values) {
        if (empty($values)) return '';
        $escaped_values = implode(',', array_map([$this->db, 'escape'], $values));
        return "AND $column IN ($escaped_values)";
    }

    private function build_day_clause($dias) {
        if (empty($dias)) return '';
        $diasMap = [
            'lunes' => 'Monday', 'martes' => 'Tuesday', 'miercoles' => 'Wednesday',
            'jueves' => 'Thursday', 'viernes' => 'Friday', 'sabado' => 'Saturday', 'domingo' => 'Sunday'
        ];
        $diasIngles = array_filter(array_map(fn($dia) => $diasMap[$dia] ?? null, $dias));
        return empty($diasIngles) ? '' : "AND DAYNAME(s.FechaDelDocumento) IN ('" . implode("','", $diasIngles) . "')";
    }

    private function build_turno_clause($turnos) {
        if (empty($turnos)) return '';
        $turnoConditions = array_map(function($turno) {
            list($start, $end) = explode('-', $turno);
            return "TIME(s.FechaDelDocumento) BETWEEN '$start' AND '$end'";
        }, $turnos);
        return "AND (" . implode(' OR ', $turnoConditions) . ")";
    }

    public function get_ventas() {
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;

        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }

        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
        
        $sql = "
         SELECT 
                se.nombre AS sede,
                COUNT(SaleId) AS TotalVentas,
                COUNT(DISTINCT PatientId) + COUNT(DISTINCT RUC) AS TotalClientesUnicos,
                SUM(
                    CASE 
                        WHEN Anulado = 1 THEN 0
                        WHEN TipoDeComprobante IN (11, 21) THEN -GlobalTotal
                        ELSE GlobalTotal
                    END
                ) AS SumaVentas
            FROM sales s 
            INNER JOIN sedes se ON s.TenantId = se.TenantId
            WHERE s.FechaDelDocumento BETWEEN ? AND ? 
            $sedeClause 
            $diaClause
            $turnoClause
            GROUP BY s.TenantId
            ORDER BY se.orden asc;

        ";

        $resultado = $this->db->query($sql, [$fecha_inicio. ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function get_productos_servicios() {
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;

        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }

        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
        
        $sql = "
            SELECT 
                se.Nombre AS Sede,
                SUM(CASE WHEN sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) AS CantidadServicios,
                SUM(CASE WHEN sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS CantidadProductos,
                AVG(CASE WHEN sd.ProductoId IS NOT NULL THEN sd.PrecioUnitario ELSE NULL END) AS PrecioPromedioProductos,
                AVG(CASE WHEN sd.ServicioId IS NOT NULL THEN sd.PrecioUnitario ELSE NULL END) AS PrecioPromedioServicios,
                -- Nueva columna: Suma de CantidadServicios y CantidadProductos
                SUM(CASE WHEN sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
                SUM(CASE WHEN sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS TotalCantidad,
                -- Nueva columna: Suma de PrecioPromedioProductos y PrecioPromedioServicios, redondeado a 2 decimales
                ROUND(
                    COALESCE(AVG(CASE WHEN sd.ProductoId IS NOT NULL THEN sd.PrecioUnitario ELSE NULL END), 0) + 
                    COALESCE(AVG(CASE WHEN sd.ServicioId IS NOT NULL THEN sd.PrecioUnitario ELSE NULL END), 0), 
                2) AS TotalPrecioPromedio
            FROM 
                sale_details sd
                INNER JOIN sales s ON sd.SaleId = s.SaleId
                INNER JOIN sedes se ON s.TenantId = se.TenantId
            WHERE   s.FechaDelDocumento BETWEEN ? AND ? 
            $sedeClause 
            $diaClause
            $turnoClause
        ";
        //   GROUP BY se.Nombre;

        $resultado = $this->db->query($sql, [$fecha_inicio. ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function get_areas_categorias() {
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;

        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }

        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
        
        $sql = "
        SELECT 
            COALESCE(NULLIF(srv.Area, ''), NULLIF(p.Area, ''), 'OTROS') AS AREA,
            COALESCE(srv.Categoria, p.Categoria) AS Categoria,
            
            -- Suma combinada de servicios y productos por sede en una sola columna
            SUM(CASE WHEN se.Nombre = 'Benavides' AND sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN se.Nombre = 'Benavides' AND sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS Benavides_TotalServiciosProductos,
            
            SUM(CASE WHEN se.Nombre = 'Jorge Chavez' AND sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN se.Nombre = 'Jorge Chavez' AND sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS JorgeChavez_TotalServiciosProductos,
            
            SUM(CASE WHEN se.Nombre = 'San Borja' AND sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN se.Nombre = 'San Borja' AND sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS SanBorja_TotalServiciosProductos,
            
            SUM(CASE WHEN se.Nombre = 'La Molina' AND sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN se.Nombre = 'La Molina' AND sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS LaMolina_TotalServiciosProductos,
            
            SUM(CASE WHEN se.Nombre = 'Magdalena' AND sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN se.Nombre = 'Magdalena' AND sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS Magdalena_TotalServiciosProductos,
            
            SUM(CASE WHEN se.Nombre = 'Pet Movil' AND sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN se.Nombre = 'Pet Movil' AND sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS PetMovil_TotalServiciosProductos,
            
            -- Total general (suma combinada de servicios y productos de todas las sedes)
            SUM(CASE WHEN sd.ServicioId IS NOT NULL THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN sd.ProductoId IS NOT NULL THEN 1 ELSE 0 END) AS TotalGeneral_ServiciosProductos,

            -- Suma de TotalConIGV por cada sede
            SUM(CASE WHEN se.Nombre = 'Benavides' THEN sd.TotalConIGV ELSE 0 END) AS Benavides_TotalConIGV,
            SUM(CASE WHEN se.Nombre = 'Jorge Chavez' THEN sd.TotalConIGV ELSE 0 END) AS JorgeChavez_TotalConIGV,
            SUM(CASE WHEN se.Nombre = 'San Borja' THEN sd.TotalConIGV ELSE 0 END) AS SanBorja_TotalConIGV,
            SUM(CASE WHEN se.Nombre = 'La Molina' THEN sd.TotalConIGV ELSE 0 END) AS LaMolina_TotalConIGV,
            SUM(CASE WHEN se.Nombre = 'Magdalena' THEN sd.TotalConIGV ELSE 0 END) AS Magdalena_TotalConIGV,
            SUM(CASE WHEN se.Nombre = 'Pet Movil' THEN sd.TotalConIGV ELSE 0 END) AS PetMovil_TotalConIGV,

            -- Total general de TotalConIGV
            SUM(sd.TotalConIGV) AS TotalGeneral_TotalConIGV

        FROM 
            sale_details sd
            INNER JOIN sales s ON sd.SaleId = s.SaleId
            INNER JOIN sedes se ON s.TenantId = se.TenantId
            LEFT JOIN servicios srv ON sd.ServicioId = srv.ServicioId
            LEFT JOIN productos p ON sd.ProductoId = p.ProductoId
        WHERE   
            s.FechaDelDocumento BETWEEN ? AND ? 
            $sedeClause 
            $diaClause
            $turnoClause
        GROUP BY 
            Categoria
        ORDER BY 
            AREA, Categoria;
        ";

        $resultado = $this->db->query($sql, [$fecha_inicio. ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function get_clientes() {
        // Obtener parámetros de entrada
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar las fechas
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Asegurarse de que las fechas estén correctamente formateadas (YYYY-MM-DD)
        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($fecha_fin));
    
        // Construir cláusulas adicionales (asegurarse que las funciones sean seguras)
        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
    
        // Preparar la consulta SQL con parámetros para prevenir inyección SQL
        $sql = "
        WITH ClientesHistoricos AS (
            SELECT 
                COALESCE(s.PatientId, s.RUC) AS Cliente,
                MIN(s.FechaDelDocumento) AS FechaPrimeraCompra
            FROM sales s
            WHERE s.PatientId IS NOT NULL OR s.RUC IS NOT NULL
            GROUP BY COALESCE(s.PatientId, s.RUC)
        ),
        ClientesConComprasDentroDelRango AS (
            SELECT 
                COALESCE(s.PatientId, s.RUC) AS Cliente,
                COUNT(*) AS ComprasDentroDelRango
            FROM sales s
            WHERE s.FechaDelDocumento BETWEEN ? AND ?
            AND (s.PatientId IS NOT NULL OR s.RUC IS NOT NULL)
            GROUP BY COALESCE(s.PatientId, s.RUC)
        ),
        ClasificacionClientes AS (
            SELECT 
                h.Cliente,
                CASE
                    WHEN h.FechaPrimeraCompra BETWEEN ? AND ? THEN 'Nuevo'
                    ELSE 'Antiguo'
                END AS Categoria
            FROM ClientesHistoricos h
        )
        SELECT 
            -- Clientes Nuevos: Clientes cuya primera compra está dentro del rango de fechas
            SUM(CASE WHEN c.Categoria = 'Nuevo' THEN 1 ELSE 0 END) AS ClientesNuevos,
            
            -- Clientes Antiguos: Clientes con compras previas al rango de fechas, que pueden haber comprado dentro del rango también
            SUM(CASE WHEN c.Categoria = 'Antiguo' AND r.ComprasDentroDelRango > 0 THEN 1 ELSE 0 END) AS ClientesAntiguos
        FROM ClasificacionClientes c
        LEFT JOIN ClientesConComprasDentroDelRango r
            ON c.Cliente = r.Cliente
        ";
    
        // Ejecutar la consulta SQL con parámetros de fecha (evitar inyección SQL)
        $resultado = $this->db->query($sql, [
            $fecha_inicio . ' 00:00:00',
            $fecha_fin . ' 23:59:59',
            $fecha_inicio . ' 00:00:00',
            $fecha_fin . ' 23:59:59'
        ])->result_array();
    
        // Verificar si hay resultados
        if (empty($resultado)) {
            return $this->output_error('No se encontraron datos para las condiciones seleccionadas');
        }
    
        // Devolver el resultado en formato JSON
        return $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }
      

    // public function get_clientes() {
    //     $request = json_decode(file_get_contents('php://input'), true);
    //     $fecha_inicio = $request['start'] ?? null;
    //     $fecha_fin = $request['end'] ?? null;
    //     $fecha_inicio_data = '2023-01-01';
    //     $fecha_corte =  date('Y-m-d', strtotime('-4 months'));
    
    //     if (!$fecha_inicio || !$fecha_fin) {
    //         return $this->output_error('Fechas inválidas');
    //     }
    
    //     // Construir cláusulas adicionales
    //     $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
    //     $diaClause = $this->build_day_clause($request['dias'] ?? []);
    //     $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
    
    //     $sql = "
    //     SELECT 
    //         SUM(CASE WHEN Categoria = 'Nuevo' THEN 1 ELSE 0 END) AS ClientesNuevos,  
    //         SUM(CASE WHEN Categoria = 'Frecuente' THEN 1 ELSE 0 END) AS ClientesFrecuentes,  
    //         SUM(CASE WHEN Categoria = 'A Recuperar' THEN 1 ELSE 0 END) AS ClientesARecuperar  
    //     FROM (
    //         SELECT 
    //             COALESCE(s.PatientId, s.RUC) AS Cliente, 
    //             CASE
    //                 WHEN COUNT(*) = 1 THEN 'Nuevo' 
    //                 WHEN COUNT(*) > 1 AND MAX(s.FechaDelDocumento) >= DATE_SUB(?, INTERVAL 3 MONTH) THEN 'Frecuente'
    //                 WHEN COUNT(*) > 0 AND MAX(s.FechaDelDocumento) < DATE_SUB(?, INTERVAL 3 MONTH) THEN 'A Recuperar' 
    //                 ELSE 'Sin Clasificar' 
    //             END AS Categoria
    //         FROM sales s
    //         WHERE s.FechaDelDocumento >= ?
    //         AND s.FechaDelDocumento >= ?
    //         AND s.FechaDelDocumento <= ?
    //         AND (s.PatientId IS NOT NULL OR s.RUC IS NOT NULL)  
    //         $sedeClause
    //         $diaClause
    //         $turnoClause
    //         GROUP BY COALESCE(s.PatientId, s.RUC)  
    //     ) AS SubConsulta;
    //     ";
    
    //     // Ejecutar la consulta con los parámetros de fecha
    //     $resultado = $this->db->query($sql, [
    //         $fecha_fin . ' 23:59:59',
    //         $fecha_fin . ' 23:59:59',
    //         $fecha_corte . ' 23:59:59',
    //         $fecha_inicio_data . ' 00:00:00',
    //         $fecha_fin . ' 23:59:59'
    //     ])->result_array();
        
    
    //     // Devolver el resultado en formato JSON
    //     $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    // }
    

    public function get_clientes_sedes() {
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
        
        $sql = "
        WITH LastPurchase AS (
            SELECT 
                s.PatientId,
                COUNT(s.SaleId) AS total_compras,
                MAX(s.FechaDelDocumento) AS ultima_compra
            FROM 
                sales s
            WHERE 
                s.FechaDelDocumento BETWEEN ? AND ?
            GROUP BY 
                s.PatientId
        ),
        ClientesClasificados AS (
            SELECT 
                lp.PatientId,
                lp.total_compras,
                lp.ultima_compra,
                CASE 
                    WHEN lp.total_compras = 1 THEN 'Cliente Nuevo'
                    WHEN lp.total_compras > 1 AND lp.ultima_compra >= NOW() - INTERVAL 3 MONTH THEN 'Cliente Frecuente'
                    WHEN lp.total_compras > 1 AND lp.ultima_compra < NOW() - INTERVAL 3 MONTH THEN 'Cliente a Recuperar'
                END AS tipo_cliente
            FROM LastPurchase lp
        )
        SELECT 
            se.nombre AS sede,
            COUNT(DISTINCT s.PatientId) AS TotalClientesUnicos,
            COUNT(DISTINCT CASE 
                WHEN cc.tipo_cliente = 'Cliente Nuevo' THEN s.PatientId
                ELSE NULL
            END) AS ClientesNuevos,
            COUNT(DISTINCT CASE 
                WHEN cc.tipo_cliente = 'Cliente Frecuente' THEN s.PatientId
                ELSE NULL
            END) AS ClientesFrecuentes,
            COUNT(DISTINCT CASE 
                WHEN cc.tipo_cliente = 'Cliente a Recuperar' THEN s.PatientId
                ELSE NULL
            END) AS ClientesARecuperar
        FROM 
            sales s
        JOIN 
            ClientesClasificados cc ON s.PatientId = cc.PatientId
        JOIN 
            sedes se ON s.TenantId = se.TenantId
        WHERE 
            s.FechaDelDocumento BETWEEN ? AND ?
        $sedeClause
        $diaClause
        $turnoClause
        GROUP BY 
            se.nombre
        ";
    
        // Ejecutar la consulta con los parámetros de fecha
        $resultado = $this->db->query($sql, [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59', $fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'])->result_array();
        
        // Devolver el resultado en formato JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }
    
    
}
?>