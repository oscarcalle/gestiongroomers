<main class="page-content">
  <div class="container-fluid">

  <div class="row">
    <div class="col-md-12">
      <div class="card shadow h-100">
        <div class="card-body">
        <h4 class="alert-heading">Bienvenido al Sistema de Gestión!</h4>
        <p>Puedes filtrar las información por sede, área, día y turno. También puedes seleccionar un rango de fechas.</p>

        <div class="row mb-3">
    <div class="col-12 col-sm-12 col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-building"></i></span>
            <select id="sedesSelect" class="selectpicker border border rounded" multiple aria-label="Sedes" data-live-search="true" title="Selecciona Sedes" data-actions-box="true">
                <option value="3121" selected>Benavides</option> 
                <option value="1037" selected>Jorge Chavez</option>
                <option value="3628" selected>La Molina</option>
                <option value="1042" selected>Magdalena</option>
                <option value="3247" selected>San Borja</option>
                <option value="3063" selected>Pet Movil</option>
            </select>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-briefcase"></i></span>
            <select id="areasSelect" class="selectpicker border border rounded" multiple aria-label="Áreas" data-live-search="true" title="Selecciona Áreas" data-actions-box="true">
                <option value="medica" selected>Médica</option> 
                <option value="grooming" selected>Grooming</option> 
                <option value="petshop" selected>Pet Shop</option> 
                <option value="seguros" selected>Seguros</option>
            </select>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            <select id="diasSelect" class="selectpicker border border rounded" multiple aria-label="Días" data-live-search="true" title="Selecciona Días" data-actions-box="true">
                <option value="lunes" selected>Lunes</option>
                <option value="martes" selected>Martes</option>
                <option value="miercoles" selected>Miercoles</option> 
                <option value="jueves" selected>Jueves</option> 
                <option value="viernes" selected>Viernes</option>
                <option value="sabado" selected>Sabado</option>
                <option value="domingo" selected>Domingo</option>
            </select>
        </div>
    </div>
    
</div>

<div class="row mb-3">
    <div class="col-12 col-sm-12 col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-clock"></i></span>
            <select id="turnosSelect" class="selectpicker border border rounded" multiple aria-label="Turnos" data-live-search="true" title="Selecciona Turnos" data-actions-box="true">
                <optgroup label="Turno Día (08:00 - 19:00)" id="diaTurno" class="toggle-optgroup">
                    <option value="08:00-09:00" selected>08:00 - 09:00</option>
                    <option value="09:00-10:00" selected>09:00 - 10:00</option>
                    <option value="10:00-11:00" selected>10:00 - 11:00</option>
                    <option value="11:00-12:00" selected>11:00 - 12:00</option>
                    <option value="12:00-13:00" selected>12:00 - 13:00</option>
                    <option value="13:00-14:00" selected>13:00 - 14:00</option>
                    <option value="14:00-15:00" selected>15:00 - 16:00</option>
                    <option value="16:00-17:00" selected>16:00 - 17:00</option>
                    <option value="17:00-18:00" selected>17:00 - 18:00</option>
                    <option value="18:00-19:00" selected>18:00 - 19:00</option>
                </optgroup>
                <optgroup label="Turno Noche (19:00 - 08:00)" id="nocheTurno" class="toggle-optgroup">
                    <option value="19:00-20:00" selected>19:00 - 20:00</option>
                    <option value="20:00-21:00" selected>20:00 - 21:00</option>
                    <option value="21:00-22:00" selected>21:00 - 22:00</option>
                    <option value="22:00-23:00" selected>22:00 - 23:00</option>
                    <option value="23:00-00:00" selected>23:00 - 00:00</option>
                    <option value="00:00-01:00" selected>00:00 - 01:00</option>
                    <option value="01:00-02:00" selected>01:00 - 02:00</option>
                    <option value="02:00-03:00" selected>02:00 - 03:00</option>
                    <option value="03:00-04:00" selected>03:00 - 04:00</option>
                    <option value="04:00-05:00" selected>04:00 - 05:00</option>
                    <option value="05:00-06:00" selected>05:00 - 06:00</option>
                    <option value="06:00-07:00" selected>06:00 - 07:00</option>
                    <option value="06:00-07:00" selected>07:00 - 08:00</option>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            <input type="text" class="form-control" name="daterange" id="daterange" title="Elige una fecha para filtrar" data-bs-toggle="tooltip" tooltip style="max-width: 225px;" />
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <button id="showSelected" class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
        <button id="descargarReporte" class="btn btn-success"><i class="fa fa-download"></i> Descargar</button>
    </div>
</div>


        </div>
      </div>
    </div>
  </div>


<!-- Content Row 1 -->
<div class="row mt-4">
  <!-- Ventas Card -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100">
      <div class="card-body">
        <div class="row no-gutters align-items-center justify-content-between">
          <div class="col-auto">
            <i class="fas fa-calendar fa-2x text-gray-300"></i>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas 2024</div>
            <div id="ventas-2024" class="text-xs font-weight-bold ">S/0</div>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas 2023</div>
            <div id="ventas-2023" class="text-xs font-weight-bold ">S/0</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Transacciones Card -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100">
      <div class="card-body">
        <div class="row no-gutters align-items-center justify-content-between">
          <div class="col-auto">
            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Transacciones 2024</div>
            <div id="transacciones-2024" class="text-xs font-weight-bold ">0</div>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Transacciones 2023</div>
            <div id="transacciones-2023" class="text-xs font-weight-bold ">0</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Promedio Card -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100">
      <div class="card-body">
        <div class="row no-gutters align-items-center justify-content-between">
          <div class="col-auto">
            <i class="fas fa-receipt fa-2x text-gray-300"></i>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ticket Promedio 2024</div>
            <div id="promedio-2024" class="text-xs font-weight-bold ">S/0</div>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ticket Promedio 2023</div>
            <div id="promedio-2023" class="text-xs font-weight-bold ">S/0</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Clientes Card -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100">
      <div class="card-body">
        <div class="row no-gutters align-items-center justify-content-between">
          <div class="col-auto">
            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Clientes 2024</div>
            <div id="clientes-2024" class="text-xs font-weight-bold ">0</div>
          </div>
          <div class="col text-center">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Clientes 2023</div>
            <div id="clientes-2023" class="text-xs font-weight-bold ">0</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


          <!-- Content Row 1 -->

          <!-- Content Row 2-->
          <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Ingreso por Area</h6>
                </div>
                <div class="card-body">

                <div class="container">
  <div class="row">
    <div class="col-12 col-md-6 mb-3">
      <canvas id="myPieChart" width="200" height="200"></canvas>
    </div>

    <div class="col-12 col-md-6">
      <table class="table table-sm table-bordered" style="font-size:12px;">
        <thead>
          <tr>
            <th>Sede</th>
            <th>Clientes</th>
            <th>TRX</th>
            <th>Monto Total</th>
            <th>Porcentaje</th>
          </tr>
        </thead>
        <tbody id="salesTableBody"></tbody>
      </table>
    </div>
  </div>
</div>


                </div>
              </div>          
            </div>

            <div class="col-lg-6 mb-4">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Acumulado</h6>
                </div>
                <div class="card-body">
                      <table class="table table-bordered" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Sede</th>
                                <th>Acum. 2023</th>
                                <th>Acum. 2024</th>
                                <th>%</th>
                                <th>Mes 10/2023</th>
                                <th>Mes 10/2024</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-datos">
                            <!-- Los datos se llenarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>
              </div> 

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Areas y Servicios</h6>
                </div>
                <div class="card-body">

                <table id="tabla_consolidado" class="table table-bordered" style="font-size:12px;">
                <!-- Aquí se generará la tabla desde JavaScript -->
              </table>
                  
                
                </div>
              </div> 

            </div>


          </div>
		 <!-- oontent row 2-->


     <div class="row">

<!-- Approach -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Total Ventas por Segmento</h6>
  </div>
  <div class="card-body">

  <canvas id="graficoVentas" width="400" height="200"></canvas>

  </div>
</div>

<!-- Approach -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Estructura de la venta</h6>
  </div>
  <div class="card-body">

  <table class="table table-sm table-bordered" style="font-size:12px;">
    <tr>
      <td rowspan="5" colspan="2" style="vertical-align: middle; text-align: center;background:#ADD8E6;">
        <div id="total_ventas">0</div> VENTAS
      </td>
      <td rowspan="3" style="vertical-align: middle; text-align: center;background:#FFB6C1;">
        <div id="total_clientes">0</div> CLIENTES
      </td>
      <td style="background:#90EE90;">
        <div id="clientes_nuevos">0</div> NUEVOS
      </td>
    </tr>
    <tr>
      <td style="background:#FFFFE0;">
        <div id="clientes_constantes">0</div> CONSTANTES
      </td>
    </tr>
    <tr>
      <td style="background:#D3D3D3;">
        <div id="clientes_recuperacion">0</div> RECUPERACIÓN
      </td>
    </tr>
    <tr>
      <td rowspan="2" style="vertical-align: middle; text-align: center;background:#E6E6FA;">
        <div id="ticket_promedio">0</div> TICKET PROMEDIO
      </td>
      <td style="background:#FFDAB9;">
        <div id="cant_serv_prod">0</div> CANT SERVICIOS/PRODUCTOS
      </td>
    </tr>
    <tr>
      <td style="background:#FFFF00;">
        <div id="precio_promedio">0</div> PRECIO PROMEDIO
      </td>
    </tr>
  </table>


  </div>
</div>

<!-- Approach -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Servicios</h6>
  </div>
  <div class="card-body" style="max-height: 500px; overflow-y:  auto;">

  <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Servicio</th>
                  <th>Cantidad</th>
              </tr>
          </thead>
          <tbody id="tabla-servicios">
              <!-- Los datos se llenarán dinámicamente aquí -->
          </tbody>
      </table>

  </div>
</div>

     </div>


</div> <!-- /.container-fluid -->
</main>


<script>

function toggleGroup(groupId) {
    const select = document.getElementById("turnosSelect");
    const optgroup = document.getElementById(groupId);
    const options = optgroup.querySelectorAll("option");
    const allSelected = Array.from(options).every(option => option.selected);

    // Alterna la selección de las opciones sin usar selectpicker('refresh')
    options.forEach(option => option.selected = !allSelected);

    // Actualiza manualmente la visualización en el componente Bootstrap Select
    const selectedOptions = Array.from(select.options)
        .filter(option => option.selected)
        .map(option => option.value);

    // Asigna los valores seleccionados directamente a Bootstrap Select
    $(select).selectpicker('val', selectedOptions);
}



$(document).ready(function() {

// Función para actualizar el título del selectpicker
  function updateSelectText(selectId, titleText) {
    var totalOptions = $(`#${selectId} option`).length; // Contar el total de opciones
    var selectedItems = $(`#${selectId}`).val(); // Obtener items seleccionados

    if (!selectedItems || selectedItems.length === 0) {
      $(`#${selectId}`).siblings('.dropdown-toggle').attr('title', `Selecciona ${titleText}`).find('.filter-option-inner-inner').text(`Selecciona ${titleText}`);
    } else if (selectedItems.length === totalOptions) {
      $(`#${selectId}`).siblings('.dropdown-toggle').attr('title', `Todos los ${titleText}`).find('.filter-option-inner-inner').text(`Todos los ${titleText}`);
    }
    // Elimina el refresh aquí
  }

  // Inicializar los selectpickers con todos los elementos seleccionados y actualizar el título
  ['sedesSelect', 'areasSelect', 'turnosSelect', 'diasSelect'].forEach(selectId => {
    $(`#${selectId} option`).prop('selected', true); // Marcar todas las opciones
    updateSelectText(selectId, selectId.replace('Select', '').toLowerCase()); // Actualizar el texto del select
  });

  // Actualizar el texto cuando se cambie la selección
  $('.selectpicker').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
    var selectId = $(this).attr('id');
    updateSelectText(selectId, selectId.replace('Select', '').toLowerCase());
  });


  $('#showSelected').on('click', function() {
        var start = $('#daterange').data('daterangepicker').startDate;
        var end = $('#daterange').data('daterangepicker').endDate;
        initEstadisticas(start, end); // Llama a la función con las fechas seleccionadas
    });

    $('#descargarReporte').on('click', function() {
        var start = $('#daterange').data('daterangepicker').startDate;
        var end = $('#daterange').data('daterangepicker').endDate;
        var sedesSelected = $('#sedesSelect').val() || '';

        var fecha_inicio = start.format('YYYY-MM-DD');
        var fecha_fin = end.format('YYYY-MM-DD');

        toastr.info('Generando Excel... Por favor espere unos segundos');
    
    // Mostrar el backdrop
    document.getElementById('loading-backdrop').style.display = 'flex';

    // Hacer la solicitud AJAX para generar el archivo
    fetch(`./excel/descargarReporteGeneral?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la red');
        }
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `reporte.xlsx`; // Nombre del archivo
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url); // Liberar el objeto URL
        
        toastr.success('Excel generado y descargado con éxito');
    })
    .catch(error => {
        toastr.error('Error al generar el Excel: ' + error.message);
    })
    .finally(() => {
        document.getElementById('loading-backdrop').style.display = 'none';
    });


    });

  $('#daterange').daterangepicker({
  opens: 'right',
  showDropdowns: true,
  minYear: 1899,
  maxYear: parseInt(moment().format('YYYY'), 10),
  ranges: {
    'Hoy': [moment(), moment()],
    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
    'Este mes': [moment().startOf('month'), moment().endOf('month')],
    'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Este año': [moment().startOf('year'), moment()],
    'Año anterior': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
  },
  locale: {
    format: 'DD/MM/YYYY',
    separator: ' - ',
    applyLabel: 'Aplicar',
    cancelLabel: 'Cancelar',
    fromLabel: 'De',
    toLabel: 'Hasta',
    customRangeLabel: 'Otro rango',
    daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    firstDay: 1
  },
  startDate: moment().subtract(6, 'days'),
  endDate: moment()
}, function (start, end) {
  initEstadisticas(start, end);
});

// Muestra las fechas iniciales al cargar la página
initEstadisticas(moment().subtract(6, 'days'), moment());

function initEstadisticas(start, end) {
  // Lógica para cargar las estadísticas
  var sedesSelected = $('#sedesSelect').val() || [];
  var areasSelected = $('#areasSelect').val() || [];
  var turnosSelected = $('#turnosSelect').val() || [];
  var diasSelected = $('#diasSelect').val() || [];


 var fecha_inicio = start.format('YYYY-MM-DD');
  var fecha_fin = end.format('YYYY-MM-DD');

            // Fetch para clientes nuevos
            fetch(`./dashboard/clientes_nuevos?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('clientes_nuevos').innerText = data[0].TotalClientesNuevos || 0;
      });

    // Fetch para clientes frecuentes
    fetch(`./dashboard/clientes_frecuentes?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('clientes_constantes').innerText = data[0].TotalClientesFrecuentes || 0;
      });

    // Fetch para clientes a recuperar
    fetch(`./dashboard/clientes_recuperar?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('clientes_recuperacion').innerText = data[0].TotalClientesRecuperar || 0;
      });

    // Fetch para totales (ventas, ticket promedio, etc.)
    fetch(`./dashboard/grafico_totales?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`)
      .then(response => response.json())
      .then(data => {
        const totales = data[0];
        document.getElementById('total_ventas').innerText = totales.SumaVentas || 0;
        document.getElementById('total_clientes').innerText = totales.TotalClientes || 0;
        document.getElementById('ticket_promedio').innerText = totales.TicketPromedio || 0;
        document.getElementById('cant_serv_prod').innerText = `${totales.CantidadServicios}/${totales.CantidadProductos}` || '0/0';
        document.getElementById('precio_promedio').innerText = `Productos: S/${totales.PrecioPromedioProductos || 0}, \n Servicios: S/${totales.PrecioPromedioServicios || 0}`;
      });

      fetch(`./dashboard/consolidado_area_categorias?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`)
  .then(response => response.json()) // Convertir la respuesta a JSON
  .then(data => {
    mostrarTabla(data); // Llamamos a la función para mostrar la tabla con los datos
    crearGraficoBarras(data); // Llamamos a la función para crear el gráfico

  })
  .catch(error => console.error('Error al obtener los datos:', error));


      fetch(`./dashboard/top_servicios?fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}&sede=${sedesSelected}`)
            .then(response => response.json())
            .then(data => {
                const tabla = document.getElementById('tabla-servicios');

                // Vaciar tabla
                tabla.innerHTML = '';

                // Recorrer los datos y generar filas dinámicamente
                data.forEach(fila => {

                    // Crear una fila HTML con los datos
                    const filaHTML = `
                        <tr>
                            <td>${fila.NombreServicio}</td>
                            <td>${fila.TotalTransacciones}</td>
                        </tr>
                    `;

                    // Insertar la fila en la tabla
                    tabla.innerHTML += filaHTML;
                });
            })
            .catch(error => console.error('Error al cargar los datos:', error));


  // Llamada a fetch a la ruta './dashboard/get_ventas'
  fetch('./dashboard/get_ventas', {
    method: 'POST', // O 'GET', según lo que necesites
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      start: start.format('YYYY-MM-DD'),
      end: end.format('YYYY-MM-DD'),
      sedes: sedesSelected,
      areas: areasSelected,
      turnos: turnosSelected,
      dias: diasSelected
    })
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json(); // O response.text(), según lo que devuelva la API
  })
  .then(data => {
    console.log('Datos recibidos:', data);
    // Aquí puedes manejar la respuesta como desees
    // Llamar a la función para crear el gráfico y la tabla con los datos dinámicos
    renderChartAndTable(data); 
  })
  .catch(error => {
    console.error('Error en la llamada fetch:', error);
  });
}

let myPieChart = null;

function renderChartAndTable(data) {
  const ctx = document.getElementById('myPieChart').getContext('2d');

  // Si ya existe un gráfico, lo destruimos antes de crear uno nuevo
  if (myPieChart !== null) {
    myPieChart.destroy();
  }

  // Extraer datos para el gráfico
  const labels = data.map(item => item.sede);
  const ventasTotales = data.map(item => parseFloat(item.SumaVentas));

  // Calcular el total de todas las ventas para el porcentaje
  const totalVentasGlobal = ventasTotales.reduce((total, venta) => total + venta, 0);

  // Crear el gráfico de pie
  myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        data: ventasTotales,
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
        hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const valor = context.raw || 0;
              const porcentaje = ((valor / totalVentasGlobal) * 100).toFixed(2);
              return `${label}: S/${valor.toFixed(2)} (${porcentaje}%)`;
            }
          }
        }
      }
    }
  });

  // Crear la tabla
  const tableBody = document.getElementById('salesTableBody');
  tableBody.innerHTML = ''; // Limpiar el cuerpo de la tabla antes de renderizar

  // Inicializar sumas
  let totalClientesUnicos = 0;
  let totalVentas = 0;
  let totalVentasSum = 0;

  data.forEach(item => {
    const totalVentasItem = parseFloat(item.SumaVentas);
    const porcentaje = ((totalVentasItem / totalVentasGlobal) * 100).toFixed(2);

    // Acumular los totales
    totalClientesUnicos += parseInt(item.TotalClientesUnicos);
    totalVentas += parseInt(item.TotalVentas);
    totalVentasSum += totalVentasItem;

    const row = `
      <tr>
        <td>${item.sede}</td>
        <td>${item.TotalClientesUnicos}</td>
        <td>${item.TotalVentas}</td>
        <td>S/ ${totalVentas.toFixed(2)}</td>
        <td>${porcentaje}%</td>
      </tr>
    `;
    tableBody.insertAdjacentHTML('beforeend', row);
  });

   // Crear la fila de sumas
   const totalRow = `
    <tr>
      <td><strong>Total</strong></td>
      <td><strong>${totalClientesUnicos}</strong></td>
      <td><strong>${totalVentas}</strong></td>
      <td><strong>S/ ${totalVentasSum.toFixed(2)}</strong></td>
      <td><strong>${((totalVentasSum / totalVentasGlobal) * 100).toFixed(2)}%</strong></td>
    </tr>
  `;
  tableBody.insertAdjacentHTML('beforeend', totalRow);
}

});


fetch('./dashboard/ventas_anuales')  
.then(response => response.json())
    .then(data => {
      // Inicializar variables para almacenar datos
      let totalVentas2023 = 0, totalVentas2024 = 0;
      let totalTransacciones2023 = 0, totalTransacciones2024 = 0;
      let promedio2023 = 0, promedio2024 = 0;
      let totalClientes2023 = 0, totalClientes2024 = 0;
      let ticketPromedio2023 = 0, ticketPromedio2024 = 0;


// Recorrer los resultados y asignar a variables según el año
data.forEach(item => {
      if (item.Año == "2023") {
        totalVentas2023 = parseFloat(item.TotalVentas); // Convertir a número
        totalTransacciones2023 = parseInt(item.TotalTransacciones); // Convertir a número
        promedio2023 = parseFloat(item.PromedioIngresos); // Convertir a número
        totalClientes2023 = parseInt(item.TotalClientes); // Convertir a número

        // Calcular el Ticket Promedio para 2023
        if (totalClientes2023 > 0) {
          ticketPromedio2023 = totalVentas2023 / totalClientes2023;
        }
      } else if (item.Año == "2024") {
        totalVentas2024 = parseFloat(item.TotalVentas); // Convertir a número
        totalTransacciones2024 = parseInt(item.TotalTransacciones); // Convertir a número
        promedio2024 = parseFloat(item.PromedioIngresos); // Convertir a número
        totalClientes2024 = parseInt(item.TotalClientes); // Convertir a número

        // Calcular el Ticket Promedio para 2024
        if (totalClientes2024 > 0) {
          ticketPromedio2024 = totalVentas2024 / totalClientes2024;
        }
      }
    });

      // Actualizar las tarjetas con Ventas
      document.getElementById('ventas-2024').textContent = "S/" + totalVentas2024.toFixed(2);   // Ventas 2024
      document.getElementById('ventas-2023').textContent = "S/" + totalVentas2023.toFixed(2);   // Ventas 2023

      // Actualizar las tarjetas con Transacciones
      document.getElementById('transacciones-2024').textContent = totalTransacciones2024;   // Transacciones 2024
      document.getElementById('transacciones-2023').textContent = totalTransacciones2023;   // Transacciones 2023

      // Actualizar las tarjetas con Promedio
      document.getElementById('promedio-2024').textContent = "S/" + ticketPromedio2024.toFixed(2);   // Promedio 2024
      document.getElementById('promedio-2023').textContent = "S/" + ticketPromedio2023.toFixed(2);   // Promedio 2023

      // Actualizar las tarjetas con Clientes
      document.getElementById('clientes-2024').textContent = totalClientes2024;   // Clientes 2024
      document.getElementById('clientes-2023').textContent = totalClientes2023;   // Clientes 2023
    })
    .catch(error => console.error('Error:', error));

     // Función para formatear los números a dos decimales
     function formatearPorcentaje(valor) {
            return valor.toFixed(2) + '%';
        }


        fetch('./dashboard/acumulado')  // Cambia la URL al endpoint correcto
    .then(response => response.json())
    .then(data => {
        const tabla = document.getElementById('tabla-datos');

        // Vaciar tabla
        tabla.innerHTML = '';

        // Inicializar sumatorias
        let total_acum_2023 = 0;
        let total_acum_2024 = 0;
        let total_mes_10_2023 = 0;
        let total_mes_10_2024 = 0;

        // Recorrer los datos y generar filas dinámicamente
        data.forEach(fila => {
            // Convertir los valores de texto a números
            const acum_2023 = parseFloat(fila.Acum_2023);
            const acum_2024 = parseFloat(fila.Acum_2024);
            const mes_10_2023 = parseFloat(fila.Mes_10_2023);
            const mes_10_2024 = parseFloat(fila.Mes_10_2024);

            // Acumular sumas
            total_acum_2023 += acum_2023;
            total_acum_2024 += acum_2024;
            total_mes_10_2023 += mes_10_2023;
            total_mes_10_2024 += mes_10_2024;

            // Calcular los porcentajes
            const porcentaje_acum = (acum_2023 > 0) ? ((acum_2024 - acum_2023) / acum_2023) * 100 : 0;
            const porcentaje_mes_10 = (mes_10_2023 > 0) ? ((mes_10_2024 - mes_10_2023) / mes_10_2023) * 100 : 0;

            // Determinar flecha y color para los porcentajes
            const flecha_acum = (porcentaje_acum >= 0) ? '▲' : '▼';
            const clase_acum = (porcentaje_acum >= 0) ? 'up' : 'down';
            
            const flecha_mes_10 = (porcentaje_mes_10 >= 0) ? '▲' : '▼';
            const clase_mes_10 = (porcentaje_mes_10 >= 0) ? 'up' : 'down';

            // Crear una fila nueva
            const filaHTML = `
                <tr>
                    <td>${fila.NombreSede}</td>
                    <td>${acum_2023.toFixed(2)}</td>
                    <td>${acum_2024.toFixed(2)}</td>
                    <td class="${clase_acum}">
                        ${formatearPorcentaje(porcentaje_acum)} ${flecha_acum}
                    </td>
                    <td>${mes_10_2023.toFixed(2)}</td>
                    <td>${mes_10_2024.toFixed(2)}</td>
                    <td class="${clase_mes_10}">
                        ${formatearPorcentaje(porcentaje_mes_10)} ${flecha_mes_10}
                    </td>
                </tr>
            `;

            // Insertar la fila en la tabla
            tabla.innerHTML += filaHTML;
        });

        // Crear la fila de sumatorias
        const filaTotalesHTML = `
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>${total_acum_2023.toFixed(2)}</strong></td>
                <td><strong>${total_acum_2024.toFixed(2)}</strong></td>
                <td></td> <!-- Celda vacía para porcentaje acumulado total -->
                <td><strong>${total_mes_10_2023.toFixed(2)}</strong></td>
                <td><strong>${total_mes_10_2024.toFixed(2)}</strong></td>
                <td></td> <!-- Celda vacía para porcentaje mes 10 total -->
            </tr>
        `;

        // Insertar la fila de sumatorias al final de la tabla
        tabla.innerHTML += filaTotalesHTML;
    })
    .catch(error => console.error('Error al cargar los datos:', error));



function mostrarTabla(data) {
  const tabla = document.getElementById('tabla_consolidado');
  tabla.innerHTML = ''; // Limpiar la tabla antes de insertar nuevos datos

  // Cabecera de la tabla
  let tablaHtml = `
    <thead>
      <tr>
        <th>Area</th>
        <th>Categoria</th>
         <th>Benavides</th>
        <th>Jorge Chavez</th>
        <th>La Molina</th>
        <th>Magdalena</th>
        <th>San Borja</th>
        <th>Petmovil</th>
        <th>Total General</th>
      </tr>
    </thead>
    <tbody>
  `;

  // Iterar sobre los datos y agregarlos a la tabla
  data.forEach(row => {
    tablaHtml += `
      <tr>
        <td>${row.Area}</td>
        <td>${row.Categoria}</td>
        <td>${row.Benavides}</td>
        <td>${row.JorgeChavez}</td>
        <td>${row.LaMolina}</td>
        <td>${row.Magdalena}</td>
        <td>${row.SanBorja}</td>
        <td>${row.Petmovil}</td>
        <td>${row.TotalGeneral}</td>
      </tr>
    `;
  });

  // Cerrar el cuerpo de la tabla
  tablaHtml += '</tbody>';

  // Insertar el HTML generado en la tabla
  tabla.innerHTML = tablaHtml;
}


// Variable para almacenar el gráfico existente (si lo hay)
let graficoBarras;

// Función para crear el gráfico de barras con Chart.js
function crearGraficoBarras(datos) {
  // Si ya existe un gráfico, destrúyelo antes de crear uno nuevo
  if (graficoBarras) {
    graficoBarras.destroy();
  }

  // Ordenar los datos por el valor de 'Total General' en orden descendente
  datos.sort((a, b) => b['Total General'] - a['Total General']);

  // Extraer etiquetas (Áreas) y datos (Total General) después de ordenarlos
  const etiquetas = datos.map(dato => dato.Area);
  const valores = datos.map(dato => dato['Total General']);

  // Crear el gráfico de barras
  const ctx = document.getElementById('graficoVentas').getContext('2d');
  graficoBarras = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: etiquetas,
      datasets: [{
        label: 'Total Ventas por Segmento S/',
        data: valores,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          display: true,
          labels: {
            color: '#000' // Color del texto de la leyenda
          }
        }
      }
    }
  });
}

</script>