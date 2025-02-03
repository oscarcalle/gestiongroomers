<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel extends CI_Controller {

    public function descargarReporteGeneral() {
        require 'vendor/autoload.php'; // Incluir esta línea
        
        // Obtener los parámetros de año y mes
        $inicio = $this->input->get('fecha_inicio');
        $fin = $this->input->get('fecha_fin');
        $sede = $this->input->get('sede');
        
        // Calcular la fecha inicial y final del mes
        $fechaInicio = "$inicio 00:00:00";
        $fechaFin = "$fin 23:59:59";
        
        // Realizar la consulta a la base de datos
        $query = $this->db->query("
            SELECT 
                se.nombre AS sede, 
                s.FechaDelDocumento AS FechaVenta,
                CASE 
                    WHEN s.Anulado = 1 THEN 'Anulada'
                    ELSE 'Emitida'
                END AS ESTADO,
                s.Descripcion,
                s.GroomersTexto1 AS Clasificacion,
                s.GroomersTexto2 AS subClasificacion,
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
                c.identificacion,
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
                CASE
                    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
                    ELSE IFNULL(SUM(p.MontoDelPago), 0)
                END AS TotalPago,
                CASE 
        WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
        ELSE MAX(CASE WHEN mp.nombre = 'Efectivo' THEN p.MontoDelPago ELSE 0 END)
    END AS PagoEfectivo,
    CASE 
        WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
        ELSE MAX(CASE WHEN mp.nombre = 'VISA' THEN p.MontoDelPago ELSE 0 END)
    END AS PagoVISA,
    CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'MasterCard' THEN p.MontoDelPago ELSE 0 END)
END AS PagoMasterCard,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Amex' THEN p.MontoDelPago ELSE 0 END)
END AS PagoAmex,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Diners' THEN p.MontoDelPago ELSE 0 END)
END AS PagoDiners,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Transferencia' THEN p.MontoDelPago ELSE 0 END)
END AS PagoTransferencia,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Cheque' THEN p.MontoDelPago ELSE 0 END)
END AS PagoCheque,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'NotaDeCredito' THEN p.MontoDelPago ELSE 0 END)
END AS PagoNotaDeCredito,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'RappiPay' THEN p.MontoDelPago ELSE 0 END)
END AS PagoRappiPay,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'AbonoPlataformas' THEN p.MontoDelPago ELSE 0 END)
END AS PagoAbonoPlataformas,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'MillasInterbank' THEN p.MontoDelPago ELSE 0 END)
END AS PagoMillasInterbank,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'PuntosVida' THEN p.MontoDelPago ELSE 0 END)
END AS PagoPuntosVida,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Yape' THEN p.MontoDelPago ELSE 0 END)
END AS PagoYape,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'MercadoPago' THEN p.MontoDelPago ELSE 0 END)
END AS PagoMercadoPago,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Plin' THEN p.MontoDelPago ELSE 0 END)
END AS PagoPlin,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'PagoWebLink' THEN p.MontoDelPago ELSE 0 END)
END AS PagoPagoWebLink,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'MovilPos' THEN p.MontoDelPago ELSE 0 END)
END AS PagoMovilPos,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'VendeMas' THEN p.MontoDelPago ELSE 0 END)
END AS PagoVendeMas,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'Izipay' THEN p.MontoDelPago ELSE 0 END)
END AS PagoIzipay,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'BBVAPOS' THEN p.MontoDelPago ELSE 0 END)
END AS PagoBBVAPOS,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'BBVAPagoWeb' THEN p.MontoDelPago ELSE 0 END)
END AS PagoBBVAPagoWeb,
CASE 
    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN 0
    ELSE MAX(CASE WHEN mp.nombre = 'BBVAPagoLink' THEN p.MontoDelPago ELSE 0 END)
END AS PagoBBVAPagoLink,
 CASE 
                    WHEN s.Anulado = 1 OR s.TipoDeComprobante IN (11, 21) THEN ''
                    ELSE p.CodigoOperacion
                END AS CodigoOperacion,
                s.UsuarioCreador,
                s.UsuarioEmision,
                s.UsuarioAnulacion,
                s.FechaAnulacion,
                s.MotivoAnulacion
            FROM sales s 
            LEFT JOIN payments p ON s.SaleId = p.SaleId
            LEFT JOIN metodo_pago mp ON p.Metodo = mp.idmetodopago
            LEFT JOIN sedes se ON s.TenantId = se.TenantId
            LEFT JOIN clientes2 c ON s.PatientId = c.patient_id
            WHERE s.FechaDelDocumento BETWEEN '$fechaInicio' AND '$fechaFin' AND s.TenantId IN ($sede)
            GROUP BY s.SaleId
            ORDER BY s.FechaDelDocumento DESC
        ");
        
        // Crear un array para almacenar los datos
        $data = [];
        
        // Procesar los resultados de la consulta
        foreach ($query->result() as $row) {
            // Separar Fecha y Hora
            $fechaCompleta = new DateTime($row->FechaVenta);
            $fecha = $fechaCompleta->format('Y-m-d');
            $hora = $fechaCompleta->format('H:i:s');
            
            // Determinar Rango
            $rango = '';
            $horaInt = (int)date('H', strtotime($hora)); // Convertir a entero la hora

            // Calcular el rango basado en la hora
            $rango = sprintf('%02d:00-%02d:00', $horaInt, ($horaInt + 1) % 24);

            // Determinar Turno
            $turno = '';
            if ($hora >= '08:00:00' && $hora < '14:00:00') {
                $turno = 'Turno Mañana'; 
            } else {
                $turno = 'Turno Noche'; 
            }
            
            // Agregar los datos al array
            $data[] = [
                'sede' => $row->sede,
                'Fecha' => $fecha,
                'Hora' => $hora,
                'Estado' => $row->ESTADO,
                'Descripcion' => $row->Descripcion,
                'Clasificacion' => $row->Clasificacion,
                'SubClasificacion' => $row->subClasificacion,
                'NumeroOrdenContrato' => $row->NumeroOrdenContrato,
                'TipoComprobante' => $row->TipoComprobante,
                'NumeroRecibo' => $row->NumeroRecibo,
                'Identificacion' => $row->identificacion,
                'Cliente' => $row->Cliente,
                'RUC' => $row->RUC,
                'RazonSocial' => $row->RazonSocial,
                'MontoSinImpuestos' => $row->MontoSinImpuestos,
                'Impuestos' => $row->Impuestos,
                'MontoTotal' => $row->MontoTotal,
                'TotalPago' => $row->TotalPago,
                'PagoEfectivo' => $row->PagoEfectivo,
                'PagoVISA' => $row->PagoVISA,
                'PagoMasterCard' => $row->PagoMasterCard,
                'PagoAmex' => $row->PagoAmex,
                'PagoDiners' => $row->PagoDiners,
                'PagoTransferencia' => $row->PagoTransferencia,
                'PagoCheque' => $row->PagoCheque,
                'PagoNotaDeCredito' => $row->PagoNotaDeCredito,
                'PagoRappiPay' => $row->PagoRappiPay,
                'PagoAbonoPlataformas' => $row->PagoAbonoPlataformas,
                'PagoMillasInterbank' => $row->PagoMillasInterbank,
                'PagoPuntosVida' => $row->PagoPuntosVida,
                'PagoYape' => $row->PagoYape,
                'PagoMercadoPago' => $row->PagoMercadoPago,
                'PagoPlin' => $row->PagoPlin,
                'PagoPagoWebLink' => $row->PagoPagoWebLink,
                'PagoMovilPos' => $row->PagoMovilPos,
                'PagoVendeMas' => $row->PagoVendeMas,
                'PagoIzipay' => $row->PagoIzipay,
                'PagoBBVAPOS' => $row->PagoBBVAPOS,
                'PagoBBVAPagoWeb' => $row->PagoBBVAPagoWeb,
                'PagoBBVAPagoLink' => $row->PagoBBVAPagoLink,
                'CodigoOperacion' => $row->CodigoOperacion,
                'UsuarioCreador' => $row->UsuarioCreador,
                'UsuarioEmision' => $row->UsuarioEmision,
                'UsuarioAnulacion' => $row->UsuarioAnulacion,
                'FechaAnulacion' => $row->FechaAnulacion,
                'MotivoAnulacion' => $row->MotivoAnulacion,
                'Rango' => $rango,
                'Turno' => $turno
            ];
        }
        
        // Exportar a Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte General');
        
        // Definir los encabezados
        $headers = [
            'Sede', 'Fecha', 'Hora', 'Estado', 'Descripción', 'Clasificación',
            'SubClasificación', 'Número Orden Contrato', 'Tipo Comprobante',
            'Número Recibo', 'Identificación', 'Cliente', 'RUC', 'Razón Social',
            'Monto Sin Impuestos', 'Impuestos', 'Monto Total', 'Total Pago',
            'Pago Efectivo', 'Pago VISA', 'Pago MasterCard', 'Pago Amex',
            'Pago Diners', 'Pago Transferencia', 'Pago Cheque', 'Pago Nota de Crédito',
            'Pago RappiPay', 'Pago Abono Plataformas', 'Pago Millas Interbank',
            'Pago Puntos Vida', 'Pago Yape', 'Pago Mercado Pago', 'Pago Plin',
            'Pago PagoWebLink', 'Pago MovilPos', 'Pago VendeMas', 'Pago Izipay',
            'Pago BBVAPOS', 'Pago BBVAPagoWeb', 'Pago Banco Continental', 
            'Pago BBVAPagoLink', 'Código Operación', 'Usuario Creador',
            'Usuario Emisión', 'Usuario Anulación', 'Fecha Anulación',
            'Rango', 'Turno'
        ];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column++ . '1', $header);
        }
        
        // Llenar los datos
        $rowNumber = 2;
        foreach ($data as $row) {
            $column = 'A';
            foreach ($row as $cell) {
                $sheet->setCellValue($column++ . $rowNumber, $cell);
            }
            $rowNumber++;
        }
        
        // Establecer el encabezado para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte_general.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Guardar el archivo y descargarlo
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
