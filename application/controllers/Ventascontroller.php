<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventascontroller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos el modelo
        $this->load->model('VentasModel');
    }

    // Método para obtener ventas agrupadas por sede
    public function obtenerVentas() {
        $request = json_decode(file_get_contents('php://input'), true);
    
        // Validar y procesar fechas
        $fecha_inicio = isset($request['start']) && strtotime($request['start']) ? $request['start'] . " 00:00:00" : null;
        $fecha_fin = isset($request['end']) && strtotime($request['end']) ? $request['end'] . " 23:59:59" : null;
    
        if (!$fecha_inicio || !$fecha_fin) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Generar cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.TenantId', $request['sedes'] ?? []);
        $diaClause = $this->build_day_clause($request['dias'] ?? []);
        $turnoClause = $this->build_turno_clause($request['turnos'] ?? []);
        $especieClause = $this->build_especie_clause($request['especies'] ?? []);
        $areaClause = $this->build_area_clause($request['areas'] ?? []);
    
        // Fecha para la tabla temporal
        $fecha = date('Ymd'); // Formato YYYYMMDD
    
        try {
            // Crear tabla temporal
            $this->VentasModel->crearTablaTemporal($fecha, $fecha_inicio, $fecha_fin, $sedeClause, $diaClause, $turnoClause, $especieClause, $areaClause);
    
            // Obtener datos
            $resultado = [
                'ventasPorSede' => $this->VentasModel->obtenerVentasPorSedeAgrupadas($fecha),
                'serviciosProductos' => $this->VentasModel->obtenerVentasPorSedeServiciosProductos($fecha),
                'ventasPorEspecie' => $this->VentasModel->obtenerVentasPorEspecieYSede($fecha),
                'ventasPorArea' => $this->VentasModel->obtenerVentasPorAreas($fecha),
            ];
        } catch (Exception $e) {
            return $this->output_error('Error al procesar datos: ' . $e->getMessage());
        } finally {
            // Eliminar tabla temporal
            $this->VentasModel->eliminarTablaTemporal($fecha);
        }
    
        // Responder con los resultados
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }    

    private function build_in_clause($column, $values) {
        // Si no se seleccionan valores o si se incluyen todas las sedes (en este caso, 6 sedes), no agregar la condición
        if (empty($values) || count($values) === 6) return '';
    
        // Escapar los valores y crear la cláusula IN
        $escaped_values = implode(',', array_map([$this->db, 'escape'], $values));
        return "AND $column IN ($escaped_values)";
    }    

    private function build_day_clause($dias) {
        // Si no se seleccionan días o se seleccionan todos los días, no agregar condición
        if (empty($dias) || count($dias) === 7) return '';
    
        $diasMap = [
            'lunes' => 'Monday', 'martes' => 'Tuesday', 'miercoles' => 'Wednesday',
            'jueves' => 'Thursday', 'viernes' => 'Friday', 'sabado' => 'Saturday', 'domingo' => 'Sunday'
        ];
        
        // Mapear los días seleccionados a su forma en inglés
        $diasIngles = array_filter(array_map(fn($dia) => $diasMap[$dia] ?? null, $dias));
        
        return empty($diasIngles) ? '' : "AND DAYNAME(FechaDelDocumento) IN ('" . implode("','", $diasIngles) . "')";
    }

    // Función para construir la cláusula de especie
    private function build_especie_clause($especies) {
        if (empty($especies)) return '';

        // Verificar si "Blanco" está en las especies y convertirlo a NULL
        $especies = array_map(function($especie) {
            return $especie === "Blanco" ? NULL : $especie;
        }, $especies);

        // Filtrar las especies que no son NULL
        $escaped_especies = array_filter($especies, function($especie) {
            return $especie !== NULL;
        });

        // Escapar las especies para la consulta
        $escaped_especies = implode(',', array_map([$this->db, 'escape'], $escaped_especies));

        // Crear la cláusula SQL base
        $clause = "AND (";

        // Si hay especies válidas (no NULL), agregar la cláusula IN
        if (!empty($escaped_especies)) {
            $clause .= "ma.especie IN ($escaped_especies)";
        }

        // Si "Blanco" estaba en las especies, agregar la condición de NULL para ma.especie
        if (in_array(NULL, $especies, true)) {
            // Si ya hay una cláusula IN, agregamos OR ma.especie IS NULL
            if (!empty($escaped_especies)) {
                $clause .= " OR ma.especie IS NULL";
            } else {
                // Si no hay especies válidas, solo agregamos la condición IS NULL
                $clause .= "ma.especie IS NULL";
            }
        }

        // Si no hay especies ni "Blanco", agregar la condición de MascotaPatientId IS NULL
        $clause .= " OR s.MascotaPatientId IS NULL)";

        return $clause;
    }

    private function build_turno_clause($turnos) {
        // Si no hay turnos o están todos (24 horas), no agregar condición
        if (empty($turnos) || count($turnos) === 24) {
            return '';
        }
    
        // Construir las condiciones para los turnos seleccionados
        $turnoConditions = array_map(function($turno) {
            list($start, $end) = explode('-', $turno);
            return "TIME(s.FechaDelDocumento) BETWEEN '$start' AND '$end'";
        }, $turnos);
    
        return "AND (" . implode(' OR ', $turnoConditions) . ")";
    }    
    
    // Función para construir la cláusula de área
    private function build_area_clause($areas) {
        if (empty($areas)) return '';
        $escaped_areas = implode(',', array_map([$this->db, 'escape'], $areas));
        return "AND (srv.Area IN ($escaped_areas) OR p.Area IN ($escaped_areas))";
    }

    // Método para mostrar error
    private function output_error($message) {
        echo json_encode(['error' => $message]);
        exit;
    }
    
    public function area_categoria() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
        
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $especies = $request['especies'] ?? [];
        $areas = $request['areas'] ?? [];
        
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.TenantId', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $especieClause = $this->build_especie_clause($especies);
        $areaClause = $this->build_area_clause($areas);
       
       $sql = "
            SELECT 
                'Petmax' AS empresa,
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
            WHERE s.FechaDelDocumento BETWEEN ? AND ?
            $sedeClause
            $diaClause
            $turnoClause
            $areaClause
            -- Subconsulta para validar especie
                AND (
                    (
                        s.MascotaPatientId IS NULL OR
                        EXISTS (
                            SELECT 1 FROM mascotas2 ma 
                            WHERE ma.patient_id = s.MascotaPatientId 
                            $especieClause
                        )
                    )
                )
           GROUP BY 
                 Area, Categoria
            ORDER BY 
                AREA, Categoria;
       ";
    
       $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin ])->result_array();
       $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function area_categoria_nombre() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
        
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $especies = $request['especies'] ?? [];
        $areas = $request['areas'] ?? [];
        
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.TenantId', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $especieClause = $this->build_especie_clause($especies);
        $areaClause = $this->build_area_clause($areas);
       
       $sql = "
            SELECT 
                'Petmax' AS empresa,
                COALESCE(NULLIF(srv.Area, ''), NULLIF(p.Area, ''), 'OTROS') AS AREA,
                COALESCE(srv.Categoria, p.Categoria) AS Categoria,
                 COALESCE(srv.Nombre, p.Nombre) AS Nombre,
                
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
            WHERE s.FechaDelDocumento BETWEEN ? AND ?
            $sedeClause
            $diaClause
            $turnoClause
            $areaClause
            -- Subconsulta para validar especie
                AND (
                    (
                        s.MascotaPatientId IS NULL OR
                        EXISTS (
                            SELECT 1 FROM mascotas2 ma 
                            WHERE ma.patient_id = s.MascotaPatientId 
                            $especieClause
                        )
                    )
                )
           GROUP BY 
                 Area, Categoria, Nombre
            ORDER BY 
                AREA, Categoria;
       ";
    
       $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin ])->result_array();
       $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function total_ventas_por_area() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $areas = $request['areas'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.TenantId', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $areaClause = $this->build_area_clause($areas);
        $especieClause = $this->build_especie_clause($especies);
    
        $sql = "
            SELECT 
               'Petmax' AS empresa, 
               COALESCE(NULLIF(srv.Area, ''), NULLIF(p.Area, ''), 'OTROS') AS AREA,
                SUM(sd.TotalConIGV) AS TotalVentas
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
                $areaClause
                -- Subconsulta para validar especie
                AND (
                    (
                        s.MascotaPatientId IS NULL OR
                        EXISTS (
                            SELECT 1 FROM mascotas2 ma 
                            WHERE ma.patient_id = s.MascotaPatientId 
                            $especieClause
                        )
                    )
                )
                    and s.Anulado = 0
            GROUP BY 
                AREA
            ORDER BY 
                AREA;
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function total_planes_salud_vendidos() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.TenantId', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $especieClause = $this->build_especie_clause($especies);
    
        $sql = "
            SELECT 
                se.Nombre AS sede,
                sv.Nombre AS Nombre,
                SUM(sd.Cantidad) AS TotalVenta
            FROM 
                sale_details sd
                INNER JOIN sales s ON sd.SaleId = s.SaleId
                INNER JOIN sedes se ON s.TenantId = se.TenantId
                LEFT JOIN servicios sv ON sd.ServicioId = sv.ServicioId
            WHERE 
                s.FechaDelDocumento BETWEEN ? AND ?
                AND sv.Nombre IN (
                    'PLAN PROTECCION SALUD PERRO',
                    'PLAN PROTECCION SALUD GATOS',
                    'PLAN PROTECCION SALUD SENIOR PERRO',
                    'PLAN PROTECCION SALUD SENIOR GATOS',
                    'RENOVACION PLAN PROTECCION SALUD',
                    'RENOVACION PLAN PROTECCION SALUD PERRO',
                    'RENOVACION PLAN PROTECCION SALUD GATOS',
                    'RENOVACION PLAN PROTECCION SALUD SENIOR SALUD PERRO',
                    'RENOVACION PLAN PROTECCION SALUD SENIOR SALUD GATOS',
                    'RENOVACION PLAN PROTECCION SALUD SENIOR GATOS',
                    'RENOVACION PLAN PROTECCION SALUD SENIOR PERRO'
                )
                $sedeClause
                $diaClause
                $turnoClause
                -- Subconsulta para validar especie
                AND (
                    (
                        s.MascotaPatientId IS NULL OR
                        EXISTS (
                            SELECT 1 FROM mascotas2 ma 
                            WHERE ma.patient_id = s.MascotaPatientId 
                            $especieClause
                        )
                    )
                )
            GROUP BY 
                sede, Nombre
            ORDER BY 
                Nombre;
        ";
    
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }

    public function meta_mes() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha enviados desde el frontend
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar que se hayan enviado las fechas correctamente
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas o faltantes');
        }
    
        // Calcular el inicio y el fin del mes basados en `start`
        $anio_inicio = date('Y', strtotime($fecha_inicio));
        $mes_inicio = date('m', strtotime($fecha_inicio));
        $fecha_inicio_mes = "$anio_inicio-$mes_inicio-01"; // Primer día del mes
        $fecha_fin_mes = date('Y-m-d', strtotime("+1 month", strtotime($fecha_inicio_mes))); // Primer día del siguiente mes
    
        // Parámetro de sedes si aplica
        $sedes = $request['sedes'] ?? [];
        $sedeClause = $this->build_in_clause('s.idsede', $sedes);
    
        $sql = "
        SELECT 
            se.nombre AS sede,
            s.meta,
            s.mes,
            s.anio,
            SUM(
                CASE 
                    WHEN sa.Anulado = 1 THEN 0
                    WHEN sa.TipoDeComprobante IN (11, 21) THEN -sa.GlobalTotal 
                    ELSE sa.GlobalTotal 
                END
            ) AS TotalVendido
        FROM 
            metas s
        INNER JOIN 
            sedes se ON s.idsede = se.TenantId
        LEFT JOIN 
            sales sa ON sa.TenantId = se.TenantId 
            AND sa.FechaDelDocumento >= ? 
            AND sa.FechaDelDocumento < ?
        WHERE 
            s.anio = ? 
            AND s.mes = ?
            $sedeClause
        GROUP BY 
            se.nombre, s.meta, s.mes, s.anio;
        ";
    
        // Ejecutar la consulta
        $resultado = $this->db->query($sql, [$fecha_inicio_mes, $fecha_fin_mes, $anio_inicio, $mes_inicio])->result_array();
    
        // Verificar si hay metas registradas en el mes
        if (empty($resultado)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'No se encontraron datos']));
            return;
        }
    
        // Cambiar el punto por coma en TotalVendido
        foreach ($resultado as &$row) {
            $row['TotalVendido'] = number_format($row['TotalVendido'], 2, ',', '');
        }
    
        // Retornar los resultados como JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }
    
    public function total_consultas_vendidos() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Parámetros de fecha
        $fecha_inicio = $request['start'] ?? null;
        $fecha_fin = $request['end'] ?? null;
    
        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
            return $this->output_error('Fechas inválidas');
        }
    
        // Agregar horas a las fechas
        $fecha_inicio .= " 00:00:00";
        $fecha_fin .= " 23:59:59";
    
        // Parámetros adicionales
        $sedes = $request['sedes'] ?? [];
        $dias = $request['dias'] ?? [];
        $turnos = $request['turnos'] ?? [];
        $especies = $request['especies'] ?? [];
    
        // Crear las cláusulas dinámicas
        $sedeClause = $this->build_in_clause('s.TenantId', $sedes);
        $diaClause = $this->build_day_clause($dias);
        $turnoClause = $this->build_turno_clause($turnos);
        $especieClause = $this->build_especie_clause($especies);
    
        // Consulta SQL para agrupar por sede y servicio
        $sql = "
            SELECT 
                se.Nombre AS sede,
                sv.Categoria AS Servicio,
                SUM(sd.Cantidad) AS TotalServiciosVendidos
            FROM 
                sale_details sd
                INNER JOIN sales s ON sd.SaleId = s.SaleId
                INNER JOIN sedes se ON s.TenantId = se.TenantId
                LEFT JOIN servicios sv ON sd.ServicioId = sv.ServicioId
            WHERE 
                s.FechaDelDocumento BETWEEN ? AND ?
                AND sv.Categoria IN (
                    'CONSULTA EXTERNA',
                    'CONSULTAS',
                    'CONSULTAS PLAN SALUD',
                    'TELECONSULTA',
                    'TELECONSULTA PLAN SALUD',
                    'CONSULTA DERMATOLOGICA',
                    'CONSULTA INTERNA-ESPECIALIDAD'
                )
                $sedeClause
                $diaClause
                $turnoClause
                -- Subconsulta para validar especie
                AND (
                    (
                        s.MascotaPatientId IS NULL OR
                        EXISTS (
                            SELECT 1 FROM mascotas2 ma 
                            WHERE ma.patient_id = s.MascotaPatientId 
                            $especieClause
                        )
                    )
                )
            GROUP BY 
                se.Nombre, sv.Categoria
            ORDER BY 
                se.orden;
        ";
    
        // Ejecutar la consulta
        $resultado = $this->db->query($sql, [$fecha_inicio, $fecha_fin])->result_array();
    
        // Devolver el resultado como JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado ?: ['error' => 'No se encontraron datos']));
    }
    
}