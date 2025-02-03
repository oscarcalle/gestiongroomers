<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Gosac extends CI_Controller {

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
        $this->load->view($data['mensaje'] ? 'error_view' : 'gosac', $data);
        $this->load->view('__footer');
    }

// Método para convertir valores de Excel a formato DATETIME
private function convertToDateTime($excelDate) {
    if (is_numeric($excelDate)) {
        // Es una fecha de Excel en formato numérico
        return date('Y-m-d H:i:s', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($excelDate));
    } else {
        // Interpretar como texto en formato DD/MM/YYYY
        $dateParts = date_parse_from_format('d/m/Y', $excelDate);
        if ($dateParts['error_count'] === 0) {
            return date('Y-m-d H:i:s', mktime(0, 0, 0, $dateParts['month'], $dateParts['day'], $dateParts['year']));
        }
        // Último recurso, intentar con strtotime
        return date('Y-m-d H:i:s', strtotime($excelDate));
    }
}



public function upload_process() {
    require 'vendor/autoload.php'; // Incluir esta línea

    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['tmp_name'];
        
        // Cargar la librería PhpSpreadsheet
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        
        try {
            $spreadsheet = $reader->load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            
            // Validar si tiene al menos 4 filas y las columnas esperadas
            if (count($sheetData) < 4 || count($sheetData[4]) != 68) {
                echo json_encode(['status' => 'error', 'message' => 'Formato de archivo inválido o columnas incorrectas.']);
                return;
            }

            // Iterar desde la fila 4 (A4)
            $data_to_insert = [];
            foreach ($sheetData as $index => $row) {
                if ($index >= 4) { // Fila 4 y siguientes
                    $serie = $row['I'];
                    $correlativo = $row['J'];

                    // Verificar si ya existe en la base de datos
                    $exists = $this->db
                        ->where('serie', $serie)
                        ->where('correlativo', $correlativo)
                        ->count_all_results('gosac');

                    if ($exists == 0) {
                        // Conversión de fechas a formato DATETIME
                        $fecha_venta = $this->convertToDateTime($row['B']);
                        $fecha_vencimiento = $this->convertToDateTime($row['C']);

                        $data_to_insert[] = [
                            'sucursal' => $row['A'],
                            'fecha_venta' => $fecha_venta,
                            'fecha_vencimiento' => $fecha_vencimiento,
                            'anulado' => $row['D'],
                            'centro_ingresos' => $row['E'],
                            'clase_doc' => $row['F'],
                            'oc' => $row['G'],
                            'sunat_tipo_comp' => $row['H'],
                            'serie' => $serie,
                            'correlativo' => $correlativo,
                            'sunat_tipo_doc' => $row['K'],
                            'ruc_documento' => $row['L'],
                            'nombre_cliente' => $row['M'],
                            'agrupador_empresa' => $row['N'],
                            'descripcion' => $row['O'],
                            'clase' => $row['P'],
                            'area' => $row['Q'],
                            'categoria' => $row['R'],
                            'codigo_int' => $row['S'],
                            'codigo_alt' => $row['T'],
                            'codigo_barra' => $row['U'],
                            'marca' => $row['V'],
                            'nombre' => $row['W'],
                            'talla' => $row['X'],
                            'color' => $row['Y'],
                            'lote_variacion' => $row['Z'],
                            'precio_lista' => $row['AA'],
                            'almacen' => $row['AB'],
                            'cantidad' => $row['AC'],
                            'unidad' => $row['AD'],
                            'moneda' => $row['AE'],
                            'costo_pp_un_ml' => $row['AF'],
                            'costo_pp_tot_ml' => $row['AG'],
                            'costo_ref_un_ml' => $row['AH'],
                            'costo_ref_tot_ml' => $row['AI'],
                            'costo_pp_un_md' => $row['AJ'],
                            'costo_pp_tot_md' => $row['AK'],
                            'costo_ref_un_md' => $row['AL'],
                            'costo_ref_tot_md' => $row['AM'],
                            'val_vta_un' => $row['AN'],
                            'margen_un' => $row['AO'],
                            'margen_un_ref' => $row['AP'],
                            'precio_vta_un' => $row['AQ'],
                            'descuentos' => $row['AR'],
                            'valor_vta_tot' => $row['AS'],
                            'valor_vta_tot_ml' => $row['AT'],
                            'margen_tot' => $row['AU'],
                            'isc' => $row['AV'],
                            'igv' => $row['AW'],
                            'precio_vta_tot' => $row['AX'],
                            'unspsc' => $row['AY'],
                            'id_proveedor' => $row['AZ'],
                            'nombre_proveedor' => $row['BA'],
                            'tc' => $row['BB'],
                            'internacional' => $row['BC'],
                            'centro_ingresos2' => $row['BD'],
                            'vendedor' => $row['BE'],
                            'vendedor_email' => $row['BF'],
                            'usuario_emisor' => $row['BG'],
                            'aceptado_sunat' => $row['BH'],
                            'sunat_error' => $row['BI'],
                            'doc_ref_tipo_comp' => $row['BJ'],
                            'doc_ref_serie' => $row['BK'],
                            'doc_ref_correlativo' => $row['BL'],
                            'doc_ref_tipo_doc' => $row['BM'],
                            'doc_ref_ruc_documento' => $row['BN'],
                            'referencia_otros_documentos' => $row['BO'],
                            'email_cliente' => $row['BP'],
                        ];
                    }
                }
            }

            // Insertar los nuevos datos
            if (!empty($data_to_insert)) {
                $this->db->insert_batch('gosac', $data_to_insert);
            }
            echo json_encode(['status' => 'success', 'message' => 'Datos cargados correctamente.']);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió ningún archivo.']);
    }
}


    public function get_data_summary() {
        date_default_timezone_set('America/Lima'); 

        $query = $this->db->query("SELECT COUNT(*) as cantidad_registros, MAX(fecha_subida) as fecha_subida FROM gosac");
    
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $fecha_subida = new DateTime($result['fecha_subida'], new DateTimeZone('UTC'));
            $fecha_subida->setTimezone(new DateTimeZone('America/Lima'));

            $result['fecha_subida'] = $fecha_subida->format('Y-m-d H:i:s');

            echo json_encode(['status' => 'success', 'data' => [$result]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No hay datos disponibles.']);
        }
    }


    public function download_excel($fecha_subida) {
        require 'vendor/autoload.php';
    
        // Obtener los registros filtrados por fecha_subida
        $query = $this->db
            ->where('DATE(fecha_subida)', $fecha_subida) // Filtrar por fecha
            ->get('gosac'); // Nombre de la tabla donde están los datos
    
        $data = $query->result_array();
    
        if (empty($data)) {
            show_404(); // Mostrar página 404 si no hay datos
        }
    
        // Crear un nuevo objeto de Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de Ventas');
    
        // Encabezados de las columnas
        $headers = [
            '#','Sucursal', 'Fecha Venta', 'Fecha Vencimiento', 'Anulado', 'Centro Ingresos',
            'Clase Doc', 'OC', 'Sunat Tipo Comp', 'Serie', 'Correlativo', 'Sunat Tipo Doc',
            'RUC Documento', 'Nombre Cliente', 'Agrupador Empresa', 'Descripción',
            'Clase', 'Área', 'Categoría', 'Código Int', 'Código Alt', 'Código Barra',
            'Marca', 'Nombre', 'Talla', 'Color', 'Lote Variación', 'Precio Lista',
            'Almacén', 'Cantidad', 'Unidad', 'Moneda', 'Costo PP Un ML', 'Costo PP Tot ML',
            'Costo Ref Un ML', 'Costo Ref Tot ML', 'Costo PP Un MD', 'Costo PP Tot MD',
            'Costo Ref Un MD', 'Costo Ref Tot MD', 'Val Vta Un', 'Margen Un',
            'Margen Un Ref', 'Precio Vta Un', 'Descuentos', 'Valor Vta Tot',
            'Valor Vta Tot ML', 'Margen Tot', 'ISC', 'IGV', 'Precio Vta Tot',
            'UNSPSC', 'ID Proveedor', 'Nombre Proveedor', 'TC', 'Internacional',
            'Centro Ingresos2', 'Vendedor', 'Vendedor Email', 'Usuario Emisor',
            'Aceptado Sunat', 'Sunat Error', 'Doc Ref Tipo Comp', 'Doc Ref Serie',
            'Doc Ref Correlativo', 'Doc Ref Tipo Doc', 'Doc Ref RUC Documento',
            'Referencia Otros Documentos', 'Email Cliente', 'Fecha subida'
        ];
    
        // Agregar los encabezados al archivo Excel
        $column = 'A'; // Inicia en la primera columna
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }
    
        // Agregar los datos filtrados
        $rowNumber = 2; // Empieza desde la segunda fila
        foreach ($data as $row) {
            $column = 'A'; // Reiniciar columna para cada fila
            foreach (array_values($row) as $value) { // Usar `array_values` para omitir índices asociativos
                $sheet->setCellValue($column . $rowNumber, $value);
                $column++;
            }
            $rowNumber++;
        }
    
        // Configurar el nombre del archivo y el encabezado HTTP para la descarga
        $filename = "lote_{$fecha_subida}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }    

    public function delete_record() {
        // Verificar si se envió la fecha de subida
        $fecha_subida = $this->input->post('fecha_subida');
    
        if ($fecha_subida) {
            // Eliminar registros asociados a esa fecha
            $this->db->where('DATE(fecha_subida)', $fecha_subida);
            if ($this->db->delete('gosac')) { // Cambia 'gosac' por el nombre real de tu tabla
                echo json_encode(['status' => 'success', 'message' => 'Registro eliminado correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al intentar eliminar el registro.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Fecha de subida no proporcionada.']);
        }
    }
    
    
    
}
?>