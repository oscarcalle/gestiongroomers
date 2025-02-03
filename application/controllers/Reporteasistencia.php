<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reporteasistencia extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(['Menu_model', 'AsignarMenu_model']);
        
         if ($this->session->userdata('status') !== "AezakmiHesoyamWhosyourdaddy") {
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
            $this->load->view('reporteasistencia', $data);
        }
    
        // Cargar el pie de página al final
        $this->load->view('__footer', $data);
    }

    public function cargar_reporte_ajax() {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        
        // Asegúrate de que las fechas sean válidas
        if (!$fecha_inicio || !$fecha_fin) {
            echo json_encode(['error' => 'Fechas inválidas']);
            return;
        }
    
        // Asegurarse de que las fechas estén correctamente formateadas en Y-m-d
        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($fecha_fin));
    
        // Ajuste para incluir el día completo de fecha_fin
        $fecha_fin = date('Y-m-d', strtotime($fecha_fin . ' +1 day')); // Asegura que el último día sea incluido
    
        $this->load->model('ReporteAsistencia_model');
        $asistencias = $this->ReporteAsistencia_model->obtener_asistencia_rango($fecha_inicio, $fecha_fin);
    
        // Inicializar datos para el reporte
        $reporte = [];
        foreach ($asistencias as $registro) {
            $empleado = $registro['nombre_completo'];
            $fecha = $registro['fecha'];
            $estado = $registro['estado'] ?? 'ausente';
    
            if (!isset($reporte[$empleado])) {
                $reporte[$empleado] = [];
            }
            if ($fecha) {
                $reporte[$empleado][$fecha] = $estado;
            }
        }
    
        // Crear una lista de fechas dentro del rango
        $fechas_rango = [];
        $startDate = new DateTime($fecha_inicio);
        $endDate = new DateTime($fecha_fin);
    
        $period = new DatePeriod(
            $startDate,
            new DateInterval('P1D'),
            $endDate // Ya incluye el último día con la modificación anterior
        );
    
        foreach ($period as $date) {
            $fechas_rango[] = $date->format('Y-m-d');
        }
    
        echo json_encode([
            'reporte' => $reporte,
            'fechas_rango' => $fechas_rango
        ]);
    }

    public function descargarExcelAsistenciaDetalladas() {
        require 'vendor/autoload.php';
    
        // Leer y validar fechas
        $request = json_decode(file_get_contents('php://input'), true);
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Consulta SQL
        $sql = "
             SELECT e.empleado_id, CONCAT(e.apellido, ' ', e.nombre) AS nombre_completo, 
               a.fecha, a.estado
            FROM empleados e
            LEFT JOIN asistencia a ON e.empleado_id = a.empleado_id
            WHERE e.ocultar_en_reporte = 'no'
            AND (a.fecha BETWEEN ? AND ? OR a.fecha IS NULL)
            ORDER BY e.apellido, e.nombre
        ";
    
        // Ejecutar consulta
        $resultado = $this->db->query($sql, ["{$fecha_inicio}", "{$fecha_fin}"])->result_array();
        if (empty($resultado)) {
            return $this->output_error('No se encontraron datos');
        }
    
        // Obtener las fechas (días) del rango
        $fechas_rango = [];
        $fechaInicio = new DateTime($fecha_inicio);
        $fechaFin = new DateTime($fecha_fin);
        while ($fechaInicio <= $fechaFin) {
            $fechas_rango[] = $fechaInicio->format('Y-m-d');
            $fechaInicio->modify('+1 day');
        }
    
        // Crear archivo Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de Asistencia');
    
        // Definir encabezados de columnas (empleado + fechas)
        $sheet->setCellValue('A1', 'Empleado');
        $columna = 'B';
        foreach ($fechas_rango as $fecha) {
            $fecha_obj = new DateTime($fecha);
            $dia_nombre = $fecha_obj->format('D'); // Día de la semana (abreviado)
            $dia_numero = $fecha_obj->format('d'); // Número del día
            $dia_nombre_espanol = [
                'Mon' => 'Lun', 'Tue' => 'Mar', 'Wed' => 'Mié', 'Thu' => 'Jue',
                'Fri' => 'Vie', 'Sat' => 'Sáb', 'Sun' => 'Dom'
            ][$dia_nombre];
            $sheet->setCellValue($columna . '1', $dia_nombre_espanol . "-" . $dia_numero);
            $columna++;
        }
    
        // Llenar los datos de los empleados y su asistencia
        $rowNumber = 2;
        $empleados = [];
        foreach ($resultado as $row) {
            // Agrupar los resultados por empleado y fecha
            $empleados[$row['empleado_id']]['nombre_completo'] = $row['nombre_completo'];
            $empleados[$row['empleado_id']]['asistencia'][$row['fecha']] = $row['estado'];
        }
    
        // Escribir los datos en el Excel
        foreach ($empleados as $empleadoId => $empleado) {
            $sheet->setCellValue('A' . $rowNumber, $empleado['nombre_completo']);
            $columna = 'B';
            foreach ($fechas_rango as $fecha) {
                $estado = $empleado['asistencia'][$fecha] ?? ''; // Si no tiene estado, dejar vacío
                if ($estado == 'presente') {
                    $sheet->setCellValue($columna . $rowNumber, '');
                    $sheet->getStyle($columna . $rowNumber)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '00FF00'], // Verde
                        ]
                    ]);
                } elseif ($estado == 'tarde') {
                    $sheet->setCellValue($columna . $rowNumber, '');
                    $sheet->getStyle($columna . $rowNumber)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFA500'], // Naranja
                        ]
                    ]);
                } elseif ($estado == 'ausente') {
                    $sheet->setCellValue($columna . $rowNumber, '');
                    $sheet->getStyle($columna . $rowNumber)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FF0000'], // Rojo
                        ]
                    ]);
                } else {
                    $sheet->setCellValue($columna . $rowNumber, ''); // Si no tiene estado, dejamos la celda vacía
                }
                $columna++;
            }
            $rowNumber++;
        }
    
        // Ajustar la columna A para que sea más ancha
        $sheet->getColumnDimension('A')->setWidth(40); // Columna A (Empleado)
    
        // Agregar bordes a toda la tabla
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Color del borde (negro)
                ],
            ],
        ];
        $sheet->getStyle('A1:' . $columna . ($rowNumber - 1))->applyFromArray($styleArray);
    
        // Exportar archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte_asistencia.xlsx"');
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
    
    
    
              
    
}
?>