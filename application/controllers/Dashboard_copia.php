<?php 

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('Menu_model'); // Cargar el modelo
		$this->load->model('AsignarMenu_model');
		
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
            $this->load->view('dashboard', $data);
        }
    
        // Cargar el pie de página al final
        $this->load->view('__footer', $data);
    }

	public function listar_sedes() {
		// Consultar los resultados almacenados en la tabla temporal
		$sql = "SELECT * FROM sedes where estado='Habilitado'";
		$query = $this->db->query($sql);
	  
		// Obtener el resultado como un array
		$resultados = $query->result_array();
	  
		// Enviar respuesta como JSON
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($resultados));
	  }

	  public function get_ventas() {
		// Obtener los parámetros del cuerpo de la solicitud POST
		$requestBody = json_decode(file_get_contents('php://input'), true);
		
		$fecha_inicio = $requestBody['start'] ?? null;
		$fecha_fin = $requestBody['end'] ?? null;
		$sedes = $requestBody['sedes'] ?? [];
		$areas = $requestBody['areas'] ?? [];
		$turnos = $requestBody['turnos'] ?? [];
		$dias = $requestBody['dias'] ?? [];
	
		// Validar fechas
		if (!$fecha_inicio || !$fecha_fin) {
			header('Content-Type: application/json');
			echo json_encode(['error' => 'Fechas inválidas']);
			return;
		}
	
		// Construir la cláusula para las sedes
		$sedeClause = '';
		if (!empty($sedes)) {
			$sedeList = implode(',', array_map([$this->db, 'escape'], $sedes));
			$sedeClause = "AND s.TenantId IN ($sedeList)";
		}
	
		// Filtrar por días
		$diaClause = '';
		if (!empty($dias)) {
			// Mapeo de días en español a inglés
			$diasMap = [
				'lunes' => 'Monday',
				'martes' => 'Tuesday',
				'miercoles' => 'Wednesday',
				'jueves' => 'Thursday',
				'viernes' => 'Friday',
				'sabado' => 'Saturday',
				'domingo' => 'Sunday'
			];

			// Convertir los días seleccionados a inglés
			$diasIngles = array_map(function($dia) use ($diasMap) {
				return isset($diasMap[$dia]) ? $diasMap[$dia] : null;
			}, $dias);

			// Filtrar los días no mapeados (si existen)
			$diasIngles = array_filter($diasIngles);

			if (!empty($diasIngles)) {
				$diaList = implode("','", array_map('trim', $diasIngles));
				$diaClause = "AND DAYNAME(s.FechaDelDocumento) IN ('$diaList')";
			}
		}
	
		// Filtrar por turnos (usando rangos de tiempo enviados desde el frontend)
		$turnoClause = '';
		if (!empty($turnos)) {
			$turnoConditions = [];
			foreach ($turnos as $turno) {
				list($startTime, $endTime) = explode('-', $turno);
				$turnoConditions[] = "TIME(s.FechaDelDocumento) BETWEEN '$startTime' AND '$endTime'";
			}
			if (!empty($turnoConditions)) {
				$turnoClause = "AND (" . implode(' OR ', $turnoConditions) . ")";
			}
		}
	
		// Consulta SQL
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
			ORDER BY SumaVentas DESC;
		";
	
		// Ejecutar la consulta
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));
	
		// Obtener resultados como array asociativo
		$resultado = $query->result_array();
	
		// Verificar si se encontraron resultados
		header('Content-Type: application/json');
		if (!empty($resultado)) {
			echo json_encode($resultado);
		} else {
			echo json_encode(['error' => 'No se encontraron datos']);
		}
	}


	public function ventas_anuales() {
		// Consultar los resultados almacenados en la tabla temporal
		$sql = "
		SELECT 
    YEAR(FechaDelDocumento) AS Año,                                     
    COUNT(DISTINCT SaleId) AS TotalTransacciones,                       
    SUM(GlobalTotal) AS TotalVentas,                                    
    AVG(GlobalTotal) AS PromedioIngresos,                               
    COUNT(DISTINCT COALESCE(PatientId, RUC)) AS TotalClientes          
FROM sales
WHERE 
    YEAR(FechaDelDocumento) IN (2023, 2024)                             
    AND Anulado = 0                                                    
GROUP BY YEAR(FechaDelDocumento);
		";
		$query = $this->db->query($sql);
	  
		// Obtener el resultado como un array
		$resultados = $query->result_array();
	  
		// Enviar respuesta como JSON
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($resultados));
	  }


	  public function acumulado() {
		// Consultar los resultados almacenados en la tabla temporal
		$sql = "
SELECT 
        s.TenantId, 
		se.nombre AS NombreSede,
        SUM(CASE WHEN YEAR(s.FechaDelDocumento) = 2023 THEN s.GlobalTotal ELSE 0 END) AS Acum_2023,
        SUM(CASE WHEN YEAR(s.FechaDelDocumento) = 2024 THEN s.GlobalTotal ELSE 0 END) AS Acum_2024,
        SUM(CASE WHEN YEAR(s.FechaDelDocumento) = 2023 AND MONTH(s.FechaDelDocumento) = 10 THEN s.GlobalTotal ELSE 0 END) AS Mes_10_2023,
        SUM(CASE WHEN YEAR(s.FechaDelDocumento) = 2024 AND MONTH(s.FechaDelDocumento) = 10 THEN s.GlobalTotal ELSE 0 END) AS Mes_10_2024
    FROM sales s inner join sedes se on s.TenantId = se.TenantId
    WHERE s.Anulado = 0 -- Ignorar ventas anuladas
    GROUP BY s.TenantId
	ORDER BY se.orden asc
		";
		$query = $this->db->query($sql);
	  
		// Obtener el resultado como un array
		$resultados = $query->result_array();
	  
		// Enviar respuesta como JSON
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($resultados));
	  }


	  public function consolidado_area_categorias() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		if($sede == ''){
		  $sede = '';
		} else {
		  $sede = "AND sa.TenantId in ('$sede')";
		}
		// Consultar los resultados almacenados en la tabla temporal
		$sql = "
SELECT 
    s.Categoria AS AREA,
    s.Nombre AS Categoria,
    SUM(CASE WHEN sede.nombre = 'Benavides' THEN sd.TotalConIGV ELSE 0 END) AS Benavides,
	SUM(CASE WHEN sede.nombre = 'Jorge Chavez' THEN sd.TotalConIGV ELSE 0 END) AS JorgeChavez,
    SUM(CASE WHEN sede.nombre = 'La Molina' THEN sd.TotalConIGV ELSE 0 END) AS 'La Molina',
    SUM(CASE WHEN sede.nombre = 'Magdalena' THEN sd.TotalConIGV ELSE 0 END) AS Magdalena,
    SUM(CASE WHEN sede.nombre = 'San Borja' THEN sd.TotalConIGV ELSE 0 END) AS 'San Borja',
    SUM(CASE WHEN sede.nombre = 'Petmovil' THEN sd.TotalConIGV ELSE 0 END) AS Petmovil,
    SUM(sd.TotalConIGV) AS 'Total General'
FROM 
    sale_details sd
JOIN 
    sales sa ON sd.SaleId = sa.SaleId
JOIN 
    servicios s ON sd.ServicioId = s.ServicioId
JOIN 
    sedes sede ON sa.TenantId = sede.TenantId  
WHERE 
    sa.Anulado = 0 AND sa.FechaDelDocumento BETWEEN  ? and ? $sede
GROUP BY 
    s.Categoria, s.Nombre
ORDER BY 
    s.Categoria, s.Nombre;

		";
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));

		// Obtener el resultado como un array
		$resultados = $query->result_array();
	  
		// Enviar respuesta como JSON
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($resultados));
	  }

	  public function grafico_totales() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		if($sede == ''){
		  $sede = '';
		} else {
		  $sede = "AND s.TenantId in ('$sede')";
		}
		// Consultar los resultados almacenados en la tabla temporal
		$sql = "
SELECT 
    FORMAT(SUM(s.GlobalTotal),2) AS SumaVentas,                                   -- Suma total de ventas
    COUNT(DISTINCT s.PatientId) AS TotalClientes,                        -- Total de clientes
    FORMAT((SUM(s.GlobalTotal) / COUNT(DISTINCT s.PatientId)),2) AS TicketPromedio,-- Ticket promedio
    COUNT(sd.ProductoId) AS CantidadProductos,                           -- Cantidad de productos vendidos
    COUNT(sd.ServicioId) AS CantidadServicios,                           -- Cantidad de servicios vendidos
    FORMAT(AVG(CASE WHEN sd.ProductoId IS NOT NULL THEN sd.PrecioUnitario END),2) AS PrecioPromedioProductos, -- Precio promedio de productos
    FORMAT(AVG(CASE WHEN sd.ServicioId IS NOT NULL THEN sd.PrecioUnitario END),2) AS PrecioPromedioServicios  -- Precio promedio de servicios
FROM sales s
LEFT JOIN sale_details sd ON s.SaleId = sd.SaleId
LEFT JOIN productos p ON sd.ProductoId = p.ProductoId
LEFT JOIN servicios sv ON sd.ServicioId = sv.ServicioId
WHERE s.FechaDelDocumento BETWEEN  ? and ? $sede
		";
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));
	  
		// Obtener el resultado como un array
		$resultados = $query->result_array();
	  
		// Enviar respuesta como JSON
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($resultados));
	  }



	  public function clientes_nuevos() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		if($sede == ''){
		  $sede = '';
		} else {
		  $sede = "AND s.TenantId in ('$sede')";
		}
		$sql = "
	  SELECT COUNT(DISTINCT c.id) AS TotalClientesNuevos
	  FROM clientes2 c
	  INNER JOIN sales s ON c.patient_id = s.PatientId
	  WHERE s.TenantId = c.tenant_id and DATE_FORMAT(s.FechaDelDocumento, '%Y-%m-%d') BETWEEN ? and ? $sede
	  AND s.FechaDelDocumento = (
		  SELECT MIN(s2.FechaDelDocumento)
		  FROM sales s2
		  WHERE s2.PatientId = c.patient_id
	  );
	  
	  ";
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));
	  
		// Obtiene los resultados como array asociativo
		$resultado = $query->result_array(); 
	  
		// Verifica si se encontraron resultados
		if (!empty($resultado)) {
			// Devuelve las opciones en formato JSON
			header('Content-Type: application/json');
			echo json_encode($resultado);
		} else {
			// No se encontraron datos, devuelve una respuesta indicando la falta de datos
			header('Content-Type: application/json');
			echo json_encode(array('error' => 'No se encontraron datos'));
		}
	  }
	  
	  public function clientes_frecuentes() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		if($sede == ''){
		  $sede = '';
		} else {
		  $sede = "AND s.TenantId in ('$sede')";
		}
		$sql = "
	  
	  
		SELECT COUNT(DISTINCT c.id) AS TotalClientesFrecuentes
	  FROM clientes2 c
	  INNER JOIN sales s ON c.patient_id = s.PatientId
	  WHERE s.TenantId = c.tenant_id
	  AND s.FechaDelDocumento >= NOW() - INTERVAL 3 MONTH AND s.FechaDelDocumento >= NOW() - INTERVAL 3 MONTH and DATE_FORMAT(s.FechaDelDocumento, '%Y-%m-%d') BETWEEN ? and ? $sede
	  
	  AND s.FechaDelDocumento > (
		  SELECT MIN(s2.FechaDelDocumento)
		  FROM sales s2
		  WHERE s2.PatientId = c.patient_id
	  );
	  
	  ";
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));
	  
		// Obtiene los resultados como array asociativo
		$resultado = $query->result_array(); 
	  
		// Verifica si se encontraron resultados
		if (!empty($resultado)) {
			// Devuelve las opciones en formato JSON
			header('Content-Type: application/json');
			echo json_encode($resultado);
		} else {
			// No se encontraron datos, devuelve una respuesta indicando la falta de datos
			header('Content-Type: application/json');
			echo json_encode(array('error' => 'No se encontraron datos'));
		}
	  }
	  
	  public function clientes_recuperar() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		if($sede == ''){
		  $sede = '';
		} else {
		  $sede = "AND s.TenantId in ('$sede')";
		}
		$sql = "
	  SELECT COUNT(DISTINCT c.id) AS TotalClientesRecuperar
	  FROM clientes2 c
	  INNER JOIN sales s ON c.patient_id = s.PatientId
	  WHERE s.TenantId = c.tenant_id
	  AND s.FechaDelDocumento < NOW() - INTERVAL 3 MONTH and DATE_FORMAT(s.FechaDelDocumento, '%Y-%m-%d') BETWEEN ? and ? $sede
	  
	  
	  ";
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));
	  
		// Obtiene los resultados como array asociativo
		$resultado = $query->result_array(); 
	  
		// Verifica si se encontraron resultados
		if (!empty($resultado)) {
			// Devuelve las opciones en formato JSON
			header('Content-Type: application/json');
			echo json_encode($resultado);
		} else {
			// No se encontraron datos, devuelve una respuesta indicando la falta de datos
			header('Content-Type: application/json');
			echo json_encode(array('error' => 'No se encontraron datos'));
		}
	  }


	  public function top_servicios() {
		$fecha_inicio = $this->input->get('fecha_inicio');
		$fecha_fin = $this->input->get('fecha_fin');
		$sede = $this->input->get('sede');
		if($sede == ''){
		  $sede = '';
		} else {
		  $sede = "AND sa.TenantId in ('$sede')";
		}
		$sql = "
	  
		 SELECT 
		  s.Nombre AS NombreServicio,
		  COUNT(sd.SaleChargeId) AS TotalTransacciones,
		  SUM(sd.TotalconIGV) AS SumaTotalconIGV,
		  sa.TenantId,
		  sed.nombre AS NombreSede
	  FROM 
		  sale_details sd
	  INNER JOIN 
		  servicios s ON sd.ServicioId = s.ServicioId
	  INNER JOIN 
		  sales sa ON sd.SaleId = sa.SaleId
	  INNER JOIN 
		  sedes sed ON sa.TenantId = sed.TenantId 
	  WHERE 
		  sd.TotalIGV IS NOT NULL AND DATE_FORMAT(sa.FechaDelDocumento, '%Y-%m-%d') BETWEEN ? and ? $sede
	  GROUP BY 
		  s.Nombre, sed.nombre
	  ORDER BY 
		  TotalTransacciones DESC
	  ";
		$query = $this->db->query($sql, array($fecha_inicio, $fecha_fin . ' 23:59:59'));
	  
		// Obtiene los resultados como array asociativo
		$resultado = $query->result_array(); 
	  
		// Verifica si se encontraron resultados
		if (!empty($resultado)) {
			// Devuelve las opciones en formato JSON
			header('Content-Type: application/json');
			echo json_encode($resultado);
		} else {
			// No se encontraron datos, devuelve una respuesta indicando la falta de datos
			header('Content-Type: application/json');
			echo json_encode(array('error' => 'No se encontraron datos'));
		}
	  }
	
	

}