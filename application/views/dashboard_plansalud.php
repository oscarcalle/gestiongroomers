<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; }
    </style>

<main class="page-content">
    <div class="container-fluid">
        
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
    <li class="breadcrumb-item"><i class="fa fa-tachometer-alt"></i> Control Panel</li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard Plan Salud</li>
  </ol>
</nav>

        <div class="row mt-0">
            <div class="col-md-12">

                    <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch gap-2">

                                <div  class="selectedpicker mt-3" style="display:none">
                                    <i class="fa fa-business-time"></i> Empresa: <br>
                                    <select id="empresaSelect" class="selectpicker border rounded" multiple 
                                        aria-label="Sedes" data-live-search="true" title="Selecciona Empresa" data-actions-box="true" >
                                        <option value="petmax" selected>Petmax</option>
                                        <option value="gosac" selected>Gosac</option>
                                    </select>
                                </div>

                                <div  class="selectedpicker mt-3">
                                    <i class="fa fa-building"></i> Sedes: <br>
                                    <select id="sedesSelect" class="selectpicker border rounded" multiple 
                                        aria-label="Sedes" data-live-search="true" title="Selecciona Sedes" data-actions-box="true" >
                                        <?php foreach ($sedes as $sede): ?>
                                        <option value="<?php echo $sede['TenantId']; ?>" selected>
                                            <?php echo $sede['nombre']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="selectedpicker mt-3"  style="display:none">
                                    <i class="fa fa-briefcase"></i> Áreas: <br>
                                    <select id="areasSelect" class="selectpicker border rounded" multiple
                                        aria-label="Áreas" data-live-search="true" title="Selecciona Áreas" data-actions-box="true">
                                        <option value="MEDICA, AREA MEDICA" selected>Médica</option>
                                        <option value="PELUQUERIA" selected>Grooming</option>
                                        <option value="PETSHOP" selected>Pet Shop</option>
                                        <option value="SEGUROS" selected>Seguros</option>
                                        <option value="OTROS, OFICINA, CONTABILIDAD, SUMINISTRO" selected>Otros</option>
                                    </select>
                                </div>

                                <div  class="selectedpicker mt-3">
                                    <i class="fa fa-paw"></i> Especie: <br>
                                    <select id="especieSelect" class="selectpicker border rounded" multiple 
                                        aria-label="Especie" data-live-search="true" title="Selecciona Especie" data-actions-box="true" >
                                        <option value="Perro" selected>Perro</option>
                                        <option value="Gato" selected>Gato</option>
                                        <option value="Canino, Macho, Hembra" selected>Otros</option>
                                        <option value="Blanco" selected>(En blanco)</option>
                                    </select>
                                </div>


                                <div class="selectedpicker mt-3">
                                    <i class="fa fa-calendar"></i> Días: <br>
                                    <select id="diasSelect" class="selectpicker border border rounded" multiple
                                        aria-label="Días" data-live-search="true" title="Selecciona Días"
                                        data-actions-box="true">
                                        <option value="lunes" selected>Lunes</option>
                                        <option value="martes" selected>Martes</option>
                                        <option value="miercoles" selected>Miercoles</option>
                                        <option value="jueves" selected>Jueves</option>
                                        <option value="viernes" selected>Viernes</option>
                                        <option value="sabado" selected>Sabado</option>
                                        <option value="domingo" selected>Domingo</option>
                                    </select>
                                </div>

                                <div style="flex: 1; min-width: 200px;height: 55px;" class="mt-3">
                                    <i class="fa fa-clock"></i> Turnos: <br>
                                    <select id="turnosSelect" class="selectpicker border border rounded" multiple
                                        aria-label="Turnos" data-live-search="true" title="Selecciona Turnos"
                                        data-actions-box="true" >
                                        <optgroup label="Turno Día (08:00 - 19:00)" data-group="dia">
                                            <option value="08:00:00-08:59:59" selected>08:00 - 09:00</option>
                                            <option value="09:00:00-09:59:59" selected>09:00 - 10:00</option>
                                            <option value="10:00:00-10:59:59" selected>10:00 - 11:00</option>
                                            <option value="11:00:00-11:59:59" selected>11:00 - 12:00</option>
                                            <option value="12:00:00-12:59:59" selected>12:00 - 13:00</option>
                                            <option value="13:00:00-13:59:59" selected>13:00 - 14:00</option>
                                            <option value="14:00:00-14:59:59" selected>14:00 - 15:00</option>
                                            <option value="15:00:00-15:59:59" selected>15:00 - 16:00</option>
                                            <option value="16:00:00-16:59:59" selected>16:00 - 17:00</option>
                                            <option value="17:00:00-17:59:59" selected>17:00 - 18:00</option>
                                            <option value="18:00:00-18:59:59" selected>18:00 - 19:00</option>
                                        </optgroup>
                                        <optgroup label="Turno Noche (19:00 - 08:00)" data-group="noche">
                                            <option value="19:00:00-19:59:59" selected>19:00 - 20:00</option>
                                            <option value="20:00:00-20:59:59" selected>20:00 - 21:00</option>
                                            <option value="21:00:00-21:59:59" selected>21:00 - 22:00</option>
                                            <option value="22:00:00-22:59:59" selected>22:00 - 23:00</option>
                                            <option value="23:00:00-23:59:59" selected>23:00 - 00:00</option>
                                            <option value="00:00:00-00:59:59" selected>00:00 - 01:00</option>
                                            <option value="01:00:00-01:59:59" selected>01:00 - 02:00</option>
                                            <option value="02:00:00-02:59:59" selected>02:00 - 03:00</option>
                                            <option value="03:00:00-03:59:59" selected>03:00 - 04:00</option>
                                            <option value="04:00:00-04:59:59" selected>04:00 - 05:00</option>
                                            <option value="05:00:00-05:59:59" selected>05:00 - 06:00</option>
                                            <option value="06:00:00-06:59:59" selected>06:00 - 07:00</option>
                                            <option value="07:00:00-07:59:59" selected>07:00 - 08:00</option>
                                        </optgroup>
                                    </select>
                                    <table>
                                        <tr>
                                            <td><div class="form-check form-switch">
                                        <input class="form-check-input d-block" type="checkbox" id="toggle-grupo1" checked>
                                        <label class="form-check-label" for="toggle-grupo1">Día</label>
                                    </div></td>
                                            <td><div class="form-check form-switch">
                                        <input class="form-check-input d-block" type="checkbox" id="toggle-grupo2" checked>
                                        <label class="form-check-label" for="toggle-grupo2">Noche</label>
                                    </div></td>
                                        </tr>
                                    </table>
                                    
                                </div>


                                <div class="selectedpicker mt-3">
                                    <i class="fa fa-calendar"></i> Rango de Fechas: <br>
                                    <input type="text" class="form-control" name="daterange" id="daterange"
                                        title="Elige una fecha para filtrar" data-toggle="tooltip" tooltip
                                        style="width: 100%;height: 41px;" />
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button id="showSelected" class="btn btn-primary" style="height: 41px;"><i class="fa fa-filter"></i> Filtrar</button>
                                    <!-- <button id="reporteGeneral" class="btn btn-success" style="height: 41px;"><i class="fa fa-file-excel"></i> Reporte</button> -->
                                    
                                </div>
                </div>
            </div>
        </div>


        <!-- Content Row 1 -->
        <div class="row mt-3 mt-sm-5">
            <!-- Clientes Nuevos Card -->
            <div class="col-xl-2 col-md-6 mb-2">
                <div class="card border-left-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-center">
                            <div class="col-auto ml-5">
                                <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-primary text-uppercase mb-1">Clientes Nuevos</div>
                                <div id="clientes_nuevos" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes Vencidos Card -->
            <div class="col-xl-2 col-md-6 mb-2">
                <div class="card border-left-danger h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-user-times fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-danger text-uppercase mb-1">Clientes Vencidos</div>
                                <div id="clientes_vencidos" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Renovaron Card -->
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-success h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-sync-alt fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-success text-uppercase mb-1">Renovaron</div>
                                <div id="clientes_renovaron" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Renovaron Card -->
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-warning text-uppercase mb-1">No Renovaron</div>
                                <div id="clientes_no_renovaron" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Por Gestionar Card -->
            <div class="col-xl-2 col-md-6 mb-2">
                <div class="card border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-info text-uppercase mb-1">Por Gestionar</div>
                                <div id="clientes_por_gestionar" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Clientes Card -->


        </div>
        </div>




        <div class="row">

         <!-- Segunda Card -->
            <div class="col-lg-6 mb-2">

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Clientes por Distrito</h5>
                    <div class="container">
                            <!-- Contenido de la primera card -->
                            <div id="map"></div>
                    </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Recuento de Cantidades por Mes y Sede</h5>
                    <div class="container">
                            <!-- Contenido de la primera card -->
                            <canvas id="ventasMesesSedesChart"></canvas>
                    </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Venta por tipo PS</h5>
                    <div class="container">
                            <!-- Contenido de la primera card -->
                            <div id="ventasPorPlanesTable"></div>
                    </div>
                    </div>
                </div>

            </div>

            <!-- Primera Card -->
            <div class="col-lg-6 mb-2">

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad por Especie/Mascota</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->
                            <canvas id="ventasChart" height="160"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad por Tipo</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->
                            <canvas id="miGrafico" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Top Vendedores PS</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->
                            <div id="topVendoresTable"></div>
                        </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad y Motivo NO Venta PS</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->
                            <div id="motivosNoVentaTable"></div>
                        </div>
                    </div>
                </div>

            </div>

           

        </div>

    </div>
</main>

<script>
    $(document).ready(function () {
        // Función para actualizar el título del selectpicker
        function updateSelectText(selectId, titleText) {
            var totalOptions = $(`#${selectId} option`).length; // Contar el total de opciones
            var selectedItems = $(`#${selectId}`).val(); // Obtener items seleccionados

            if (!selectedItems || selectedItems.length === 0) {
                $(`#${selectId}`).siblings('.dropdown-toggle').attr('title', `Selecciona ${titleText}`).find('.filter-option-inner-inner').text(`Selecciona ${titleText}`);
            } else if (selectedItems.length === totalOptions) {
                $(`#${selectId}`).siblings('.dropdown-toggle').attr('title', `All ${titleText}`).find('.filter-option-inner-inner').text(`All ${titleText}`);
            }
            // Elimina el refresh aquí
        }

        // Inicializar los selectpickers con todos los elementos seleccionados y actualizar el título
        ['sedesSelect', 'areasSelect', 'turnosSelect', 'diasSelect'].forEach(selectId => {
            $(`#${selectId} option`).prop('selected', true); // Marcar todas las opciones
            updateSelectText(selectId, selectId.replace('Select', '').toLowerCase()); // Actualizar el texto del select
        });

        // Actualizar el texto cuando se cambie la selección
        $('.selectpicker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            var selectId = $(this).attr('id');
            updateSelectText(selectId, selectId.replace('Select', '').toLowerCase());
        });

         // Función para seleccionar todas las opciones de un grupo
         function selectGroup(selectId, groupName) {
                $(`#${selectId} optgroup[label="${groupName}"] option`).prop('selected', true);
                $(`#${selectId}`).selectpicker('refresh'); // Refrescar el componente
            }

            // Función para deseleccionar todas las opciones de un grupo
            function deselectGroup(selectId, groupName) {
                $(`#${selectId} optgroup[label="${groupName}"] option`).prop('selected', false);
                $(`#${selectId}`).selectpicker('refresh'); // Refrescar el componente
            }

            // Toggle para Grupo 1
            $('#toggle-grupo1').on('change', function () {
                if (this.checked) {
                    selectGroup('turnosSelect', 'Turno Día (08:00 - 19:00)');
                } else {
                    deselectGroup('turnosSelect', 'Turno Día (08:00 - 19:00)');
                }
            });

            // Toggle para Grupo 2
            $('#toggle-grupo2').on('change', function () {
                if (this.checked) {
                    selectGroup('turnosSelect', 'Turno Noche (19:00 - 08:00)');
                } else {
                    deselectGroup('turnosSelect', 'Turno Noche (19:00 - 08:00)');
                }
            });
        

        $('#showSelected').on('click', function () {
            const { startDate: start, endDate: end } = $('#daterange').data('daterangepicker');
            initEstadisticas(start, end);
        });

        $('#daterange').daterangepicker({
            opens: 'right',
            showDropdowns: true,
            minYear: 1899,
            maxYear: moment().year(),
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
            //startDate: moment().subtract(6, 'days'),
            startDate: moment(),
            endDate: moment()
        }, initEstadisticas);

        // Llamada a la función para actualizar las estadísticas
        initEstadisticas(moment(), moment());

// Mostrar/Ocultar loader
function toggleLoader(show) {
    $('#loader').css('visibility', show ? 'visible' : 'hidden');
}

// Función para obtener datos de la solicitud
function getFetchData() {
    // Áreas: separar por coma si es necesario
    let areas = $('#areasSelect').val() || [];
    areas = areas.flatMap(area => area.split(',').map(a => a.trim()));  // Separa por coma y elimina espacios extra

    // Especies: separar por coma si es necesario
    let especies = $('#especieSelect').val() || [];
    especies = especies.flatMap(especie => especie.split(',').map(e => e.trim()));  // Separa por coma y elimina espacios extra

    return {
        start: $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'),
        end: $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'),
        empresas: $('#empresaSelect').val() || [],
        sedes: $('#sedesSelect').val() || [],
        areas: areas,  // Áreas separadas correctamente
        especies: especies,  // Especies separadas correctamente
        turnos: $('#turnosSelect').val() || [],
        dias: $('#diasSelect').val() || []
    };
}

function validarSeleccion(fetchData) {
    if (!fetchData.empresas.length) {
        Swal.fire('Error', 'Por favor selecciona al menos una empresa.', 'error');
        return false;
    }
    if (!fetchData.sedes.length) {
        Swal.fire('Error', 'Por favor selecciona al menos una sede.', 'error');
        return false;
    }
    if (!fetchData.areas.length) {
        Swal.fire('Error', 'Por favor selecciona al menos un área.', 'error');
        return false;
    }
    if (!fetchData.especies.length) {
        Swal.fire('Error', 'Por favor selecciona al menos una especie.', 'error');
        return false;
    }
    if (!fetchData.turnos.length) {
        Swal.fire('Error', 'Por favor selecciona al menos un turno.', 'error');
        return false;
    }
    if (!fetchData.dias.length) {
        Swal.fire('Error', 'Por favor selecciona al menos un día.', 'error');
        return false;
    }
    return true;
}

// Función común para realizar solicitudes Fetch
function fetchDataAndProcess(url, fetchData, onSuccess) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(fetchData),
    })
        .then(response => {
            if (!response.ok) {
                // Si hay un error de red (como 404 o 500)
                throw new Error(`Error de red: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            // Verificar si hay un error o si los datos están vacíos
            if (data && data.error) {
                console.warn(`Endpoint ${url} devolvió un error esperado:`, data.error);
                // Mostrar un mensaje de error o ejecutar alguna acción si se define
                if (onSuccess) onSuccess(null, data.error);
                return null; // Devolvemos null para errores
            }

            // Si no hay error, se ejecuta la función de éxito si está definida
            if (onSuccess) onSuccess(data);
            return data;
        })
        .catch(error => {
            console.error(`Error en la llamada fetch a ${url}:`, error);
            // También puedes ejecutar alguna acción en caso de error de red
            if (onSuccess) onSuccess(null, 'Error de red');
            throw error; // Rechazamos para errores reales de red
        });
}


let miGraficoChart; // Variable global para almacenar el gráfico

let map;  // Define la variable del mapa fuera de cualquier función

function initMap() {
    // Verifica si el mapa ya ha sido inicializado
    if (map) {
        map.remove(); // Remueve el mapa existente antes de volver a inicializarlo
    }
    
    // Crear el mapa y establecer su vista
    map = L.map('map').setView([ -12.0464, -77.0428 ], 13); // Ajusta las coordenadas

    // Añadir el tile layer de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

initMap(); // Inicializa el mapa

// Función principal para inicializar estadísticas
function initEstadisticas(start, end) {
    const fetchData = getFetchData();



     // Validar antes de continuar
     if (!validarSeleccion(fetchData)) {
        return;
    }

    toggleLoader(true);

    fetchDataAndProcess('./dashboard/get_clientes', fetchData, data => {
        document.getElementById('clientes_nuevos').textContent = data[0].ClientesNuevos;
        // document.getElementById('clientes_constantes').textContent = data[0].ClientesFrecuentes;
        // document.getElementById('clientes_recuperacion').textContent = data[0].ClientesARecuperar;
    });

    fetchDataAndProcess('./dashboard_plansalud/cantidad_motivo_noventa', fetchData, data => {
        renderTableMotivosNoVenta(data);
    });

    fetchDataAndProcess('./dashboard_plansalud/reporte_planes_vencidos', fetchData, data => {


        countUp('clientes_vencidos', 0, data[0].clientes_vencidos, 2000);
        countUp('clientes_renovaron', 0, data[0].clientes_renovaron, 2000);
        countUp('clientes_no_renovaron', 0, data[0].clientes_no_renovaron, 2000);
        countUp('clientes_por_gestionar', 0, data[0].clientes_por_gestionar, 2000);
    });

    fetchDataAndProcess('./dashboard_plansalud/obtenerVentas', fetchData, data => {

        renderChartCantidadEspecie(data.ventasPorEspecie);
        renderChartCantidadPlanesSalud(data.ventasPorPlanes);
        renderTableVentasPorPlanes(data.ventasPorPlanes);
        renderTableTopVendores(data.topVendedores);
        renderChartVentasPorMesesYSedes(data.ventasPorMesesYSedes);

    })
    .catch(() => {
        toastr.error('No hay datos de ventas para el rango de fechas seleccionado.');
    })
    .finally(() => {
        // Esto se ejecutará siempre al final
        toggleLoader(false);
    });

}

// Función para manejar la descarga de Excel
function descargarExcel(url, fileName) {
    const fetchData = getFetchData();
    toastr.info('Generando Excel... Por favor espere unos segundos');
    document.getElementById('loading-backdrop').style.display = 'flex';

    fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' },
        body: JSON.stringify(fetchData)
    })
        .then(response => {
            if (!response.ok) throw new Error('Error en la red');
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            toastr.success('Excel generado y descargado con éxito');
        })
        .catch(error => {
            toastr.error('Error al generar el Excel: ' + error.message);
        })
        .finally(() => {
            document.getElementById('loading-backdrop').style.display = 'none';
        });
}

// Funciones específicas para descargas
function descargarExcelGeneral() {
    descargarExcel('./dashboard/descargarExcel', 'reporte.xlsx');
}

function descargarExcelVentasDetalladas() {
    descargarExcel('./dashboard/descargarExcelVentasDetalladas', 'reporte_ventas_detalladas.xlsx');
}

$('#reporteGeneral').on('click', function () {
        descargarExcelGeneral();
});

$('#reporteVentasDetalladasButton').on('click', function () {
    descargarExcelVentasDetalladas();
});

let ventasChart = null;

function renderChartCantidadEspecie(data) {
    if (data.error) {
        Swal.fire('Error', data.error, 'error');
    } else {
        // Procesar datos de ventasPorEspecie
        const ventasPorEspecie = data;

        // Extraer nombres de especies y valores totales de ventas
        const especies = ventasPorEspecie.map(item => item.especie);
        const totalVentas = ventasPorEspecie.map(item => parseFloat(item.TotalVentas));

        // Definir colores para cada especie
        const colores = ['orange', 'skyblue', 'green', 'purple', 'red']; // Puedes agregar más colores si hay más especies

        // Verificar si ya existe el gráfico y destruirlo
        if (ventasChart) {
            ventasChart.destroy();
        }

        // Configurar y renderizar el gráfico de donut
        const ctx = document.getElementById('ventasChart').getContext('2d');
        ventasChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: especies,
                datasets: [
                    {
                        data: totalVentas,
                        backgroundColor: colores.slice(0, especies.length),
                    }
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Ventas por Especie'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value} ventas`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}


const abreviacionesCategorias = {
    "PLAN PROTECCION SALUD GATOS": "PP SALUD GATOS",
    "PLAN PROTECCION SALUD SENIOR GATOS": "PP SALUD SENIOR GATOS",
    "PLAN PROTECCION SALUD SENIOR PERRO": "PP SALUD SENIOR PERRO",
    "PLAN PROTECCION SALUD PERRO": "PP SALUD PERRO",
    "RENOVACION PROTECCION SALUD": "RENOVACION PP SALUD",
    "RENOVACION PROTECCION SALUD SENIOR": "RENOVACION PP SALUD SENIOR",
    "RENOVACION PLAN PROTECCION SALUD": "RENOVACION PP SALUD",
    "RENOVACION PLAN PROTECCION SALUD PERRO": "RENOVACION PP SALUD PERRO",
    "RENOVACION PLAN PROTECCION SALUD GATOS": "RENOVACION PP SALUD GATOS",
    "RENOVACION PLAN PROTECCION SALUD SENIOR SALUD PERRO": "RENOVACION PP SALUD SENIOR PERRO",
    "RENOVACION PLAN PROTECCION SALUD SENIOR SALUD GATOS": "RENOVACION PP SALUD SENIOR GATOS",
    "RENOVACION PLAN PROTECCION SALUD SENIOR GATOS": "RENOVACION PP SALUD SENIOR GATOS",
    "RENOVACION PLAN PROTECCION SALUD SENIOR PERRO": "RENOVACION PP SALUD SENIOR PERRO"
};

function renderChartCantidadPlanesSalud(data) {
    // Procesar datos de ventasPorPlanes
    const ventasPorPlanes = data;

    // Extraer categorías y valores totales de ventas
    const categorias = ventasPorPlanes.map(item => item.PlanNombre);
    const totalVentas = ventasPorPlanes.map(item => parseFloat(item.TotalVentas));

    // Aplicar abreviaciones a los nombres de las categorías
    const abreviacionesCategorias = {
        "PLAN PROTECCION SALUD GATOS": "PP SALUD GATOS",
        "PLAN PROTECCION SALUD SENIOR GATOS": "PP SALUD SENIOR GATOS",
        "PLAN PROTECCION SALUD SENIOR PERRO": "PP SALUD SENIOR PERRO",
        "PLAN PROTECCION SALUD PERRO": "PP SALUD PERRO",
        "RENOVACION PROTECCION SALUD": "RENOVACION PP SALUD",
        "RENOVACION PROTECCION SALUD SENIOR": "RENOVACION PP SALUD SENIOR",
        "RENOVACION PLAN PROTECCION SALUD": "RENOVACION PP SALUD",
        "RENOVACION PLAN PROTECCION SALUD PERRO": "RENOVACION PP SALUD PERRO",
        "RENOVACION PLAN PROTECCION SALUD GATOS": "RENOVACION PP SALUD GATOS",
        "RENOVACION PLAN PROTECCION SALUD SENIOR SALUD PERRO": "RENOVACION PP SALUD SENIOR PERRO",
        "RENOVACION PLAN PROTECCION SALUD SENIOR SALUD GATOS": "RENOVACION PP SALUD SENIOR GATOS",
        "RENOVACION PLAN PROTECCION SALUD SENIOR GATOS": "RENOVACION PP SALUD SENIOR GATOS",
        "RENOVACION PLAN PROTECCION SALUD SENIOR PERRO": "RENOVACION PP SALUD SENIOR PERRO"
    };

    const categoriasAbreviadas = categorias.map(categoria => abreviacionesCategorias[categoria] || categoria);

    // Definir colores para las categorías
    const colores = ['#FF5733', '#FFC300', '#DAF7A6', '#C70039', '#581845', '#28B463', '#3498DB'];

    // Verificar si ya existe el gráfico y destruirlo
    if (miGraficoChart) {
        miGraficoChart.destroy();
    }

    // Configurar y renderizar el gráfico de barra
    const ctx = document.getElementById('miGrafico').getContext('2d');
    miGraficoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categoriasAbreviadas,
            datasets: [
                {
                    label: 'Ventas por Plan',
                    data: totalVentas,
                    backgroundColor: colores.slice(0, categorias.length),
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Ventas por Plan de Salud'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value} ventas`;
                        }
                    }
                },
                legend: {
                    display: false // Ocultar leyenda ya que solo hay un dataset
                }
            },
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Planes de Salud'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Ventas'
                    }
                }
            }
        }
    });
}


function renderTableVentasPorPlanes(data) {
    // Obtener el contenedor de la tabla
    const tableContainer = document.getElementById('ventasPorPlanesTable');

    // Limpiar el contenido previo
    tableContainer.innerHTML = '';

    // Crear la tabla
    const table = document.createElement('table');
    table.classList.add('table', 'table-bordered');

    // Crear el encabezado de la tabla
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['Plan', 'Total Ventas', 'Suma Ventas'];
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Crear el cuerpo de la tabla
    const tbody = document.createElement('tbody');
    let totalVentas = 0; // Variable para calcular el total de ventas

    data.forEach(item => {
        const row = document.createElement('tr');

        // Columna de Plan (abreviado si corresponde)
        const planCell = document.createElement('td');
        planCell.textContent = abreviacionesCategorias[item.PlanNombre] || item.PlanNombre;
        row.appendChild(planCell);

        // Columna de Total Ventas
        const ventasCell = document.createElement('td');
        ventasCell.textContent = item.TotalVentas;
        ventasCell.style.textAlign = 'right'; // Alinear los números a la derecha
        row.appendChild(ventasCell);

        // Columna de Suma Ventas
        const sumaVentasCell = document.createElement('td');
        sumaVentasCell.textContent = parseFloat(item.SumaVentas).toFixed(2); // Asegurarse de tener dos decimales
        sumaVentasCell.style.textAlign = 'right'; // Alinear los números a la derecha
        row.appendChild(sumaVentasCell);

        tbody.appendChild(row);

        // Sumar al total
        totalVentas += parseFloat(item.SumaVentas); // Sumamos la "SumaVentas" si es el total correcto
    });
    table.appendChild(tbody);

    // Crear el pie de tabla (tfoot) para la suma total
    const tfoot = document.createElement('tfoot');
    const footerRow = document.createElement('tr');

    // Celda de descripción (colspan para que ocupe toda la fila)
    const totalLabelCell = document.createElement('td');
    totalLabelCell.textContent = 'Total Ventas';
    totalLabelCell.style.fontWeight = 'bold';
    totalLabelCell.setAttribute('colspan', '2'); // Ocupa dos columnas
    totalLabelCell.style.textAlign = 'right'; // Alinear a la derecha
    footerRow.appendChild(totalLabelCell);

    // Celda del total de ventas
    const totalValueCell = document.createElement('td');
    totalValueCell.textContent = totalVentas.toFixed(2); // Mostrar el total de ventas con dos decimales
    totalValueCell.style.fontWeight = 'bold';
    totalValueCell.style.textAlign = 'right'; // Alinear a la derecha
    footerRow.appendChild(totalValueCell);

    tfoot.appendChild(footerRow);
    table.appendChild(tfoot);

    // Agregar la tabla al contenedor
    tableContainer.appendChild(table);
}


function renderTableTopVendores(data) {
    // Obtener el contenedor de la tabla
    const tableContainer = document.getElementById('topVendoresTable');

    // Limpiar el contenido previo
    tableContainer.innerHTML = '';

    // Crear la tabla
    const table = document.createElement('table');
    table.classList.add('table', 'table-bordered');

    // Crear el encabezado de la tabla
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['Usuario', 'Total Transacciones', 'Total Ventas', 'Sede'];
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Crear el cuerpo de la tabla
    const tbody = document.createElement('tbody');
    let totalTransacciones = 0; // Suma de transacciones
    let totalVentas = 0; // Suma de ventas

    data.forEach(item => {
        const row = document.createElement('tr');

        // Crear el ícono
        const icon = document.createElement('i');
        icon.classList.add('fa', 'fa-user');

        // Columna de Usuario
        const usuarioCell = document.createElement('td');
        const cellContent = document.createElement('span'); // Contenedor para el ícono y texto
        cellContent.appendChild(icon);
        cellContent.appendChild(document.createTextNode(' ' + item.UsuarioEmision));  // Espacio antes del texto
        usuarioCell.appendChild(cellContent);  // Agregar el contenido a la celda
        row.appendChild(usuarioCell);  // Agregar la celda a la fila

        // Columna de Total Transacciones
        const transaccionesCell = document.createElement('td');
        transaccionesCell.textContent = item.TotalTransacciones;
        row.appendChild(transaccionesCell);
        totalTransacciones += parseInt(item.TotalTransacciones);

        // Columna de Total Ventas
        const ventasCell = document.createElement('td');
        ventasCell.textContent = parseFloat(item.TotalVentas).toFixed(2);
        row.appendChild(ventasCell);
        totalVentas += parseFloat(item.TotalVentas);

        // Columna de Sede
        const sedeCell = document.createElement('td');
        sedeCell.textContent = item.NombreSede;
        row.appendChild(sedeCell);

        // Agregar la fila al cuerpo de la tabla
        tbody.appendChild(row);
    });

    // Finalmente, agregar el cuerpo de la tabla a la tabla
    table.appendChild(tbody);


    // Crear el pie de tabla (tfoot) para las sumas
    const tfoot = document.createElement('tfoot');
    const footerRow = document.createElement('tr');

    // Celda de descripción
    const totalLabelCell = document.createElement('td');
    totalLabelCell.textContent = 'Totales';
    totalLabelCell.style.fontWeight = 'bold';
    footerRow.appendChild(totalLabelCell);

    // Celda de total de transacciones
    const totalTransaccionesCell = document.createElement('td');
    totalTransaccionesCell.textContent = totalTransacciones;
    totalTransaccionesCell.style.fontWeight = 'bold';
    footerRow.appendChild(totalTransaccionesCell);

    // Celda de total de ventas
    const totalVentasCell = document.createElement('td');
    totalVentasCell.textContent = totalVentas.toFixed(2);
    totalVentasCell.style.fontWeight = 'bold';
    footerRow.appendChild(totalVentasCell);

    // Celda vacía para Sede
    const emptyCell = document.createElement('td');
    footerRow.appendChild(emptyCell);

    tfoot.appendChild(footerRow);
    table.appendChild(tfoot);

    // Agregar la tabla al contenedor
    tableContainer.appendChild(table);
}

function renderChartVentasPorMesesYSedes(data) {
    // Obtener el contexto del canvas
    const ctx = document.getElementById('ventasMesesSedesChart').getContext('2d');

    // Verificar si ya existe un gráfico y destruirlo si es necesario
    if (window.chartInstance) {
        window.chartInstance.destroy();
    }

    // Agrupar las ventas por mes, sede y plan
    const groupedData = {};

    data.forEach(item => {
        const key = `${item.Mes}-${item.Sede}`; // Agrupamos por Mes y Sede
        if (!groupedData[key]) {
            groupedData[key] = {
                Sede: item.Sede,
                Mes: item.Mes,
                TotalVentas: 0
            };
        }
        // Sumar las ventas por la misma combinación de Mes y Sede
        groupedData[key].TotalVentas += parseInt(item.TotalVentas);
    });

    // Convertir el objeto agrupado en un array para trabajar con él
    const groupedArray = Object.values(groupedData);

    // Extraer los meses únicos y las sedes únicas
    const Meses = [...new Set(groupedArray.map(item => item.Mes))];
    const Sedes = [...new Set(groupedArray.map(item => item.Sede))];

    // Generar colores para las sedes
    const colores = ['#3498db', '#1abc9c', '#9b59b6', '#e74c3c', '#f1c40f', '#2ecc71'];

    // Crear datasets para cada sede
    const datasets = Sedes.map((Sede, index) => ({
        label: Sede, // Nombre de la sede
        backgroundColor: colores[index % colores.length],
        data: Meses.map(Mes => {
            // Buscar el valor correspondiente para esta sede y mes
            const item = groupedArray.find(d => d.Sede === Sede && d.Mes === Mes);
            return item ? item.TotalVentas : 0;
        })
    }));

    // Configuración del gráfico
    const config = {
        type: 'bar',
        data: {
            labels: Meses,
            datasets: datasets
        },
        options: {
            plugins: {
                legend: {
                    position: 'top', // Posición de la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true, // Hacer las barras apiladas
                    title: {
                        display: true,
                        text: 'Mes'
                    }
                },
                y: {
                    stacked: true, // Hacer las barras apiladas
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Recuento de Ventas'
                    }
                }
            }
        }
    };

    // Crear el gráfico
    window.chartInstance = new Chart(ctx, config);
}

function renderTableMotivosNoVenta(data) {
    // Obtener el contenedor de la tabla
    const tableContainer = document.getElementById('motivosNoVentaTable');

    // Limpiar el contenido previo
    tableContainer.innerHTML = '';

    // Crear la tabla
    const table = document.createElement('table');
    table.classList.add('table', 'table-bordered');

    // Crear el encabezado de la tabla
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['Motivo', 'Cantidad'];
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Crear el cuerpo de la tabla
    const tbody = document.createElement('tbody');
    let totalCantidad = 0;  // Variable para acumular la cantidad total

    data.forEach(item => {
        const row = document.createElement('tr');

        // Columna de Motivo
        const motivoCell = document.createElement('td');
        motivoCell.textContent = item.motivo || 'No especificado';  // Mostrar 'No especificado' si el motivo es vacío o null
        row.appendChild(motivoCell);

        // Columna de Cantidad
        const cantidadCell = document.createElement('td');
        cantidadCell.textContent = item.cantidad;
        row.appendChild(cantidadCell);

        // Sumar la cantidad al total
        totalCantidad += parseInt(item.cantidad);

        // Agregar la fila al cuerpo de la tabla
        tbody.appendChild(row);
    });

    // Finalmente, agregar el cuerpo de la tabla a la tabla
    table.appendChild(tbody);

    // Crear el pie de tabla (tfoot) para la suma total de cantidad
    const tfoot = document.createElement('tfoot');
    const footerRow = document.createElement('tr');

    // Celda vacía para "Motivo"
    const emptyCell = document.createElement('td');
    emptyCell.textContent = 'Total';
    emptyCell.style.fontWeight = 'bold';
    footerRow.appendChild(emptyCell);

    // Celda con la suma de las cantidades
    const totalCantidadCell = document.createElement('td');
    totalCantidadCell.textContent = totalCantidad;
    totalCantidadCell.style.fontWeight = 'bold';
    footerRow.appendChild(totalCantidadCell);

    tfoot.appendChild(footerRow);
    table.appendChild(tfoot);

    // Agregar la tabla al contenedor
    tableContainer.appendChild(table);
}

function formatCurrency(number) {
    // Si es un número entero, no mostrar los decimales
    if (Number.isInteger(number)) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'"); // Agrega el separador de miles
    }

    // Si no es entero, formatear con 2 decimales
    return number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, "'"); // Agrega el separador de miles
}

// Función para realizar el conteo progresivo
function countUp(elementId, start, end, duration, prefix = '') {
    const element = document.getElementById(elementId);
    let startTime = null;

    // Función de animación
    function animateCount(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        
        // Calculamos el valor actual basado en el tiempo de la animación
        const currentValue = Math.min(start + (end - start) * (progress / duration), end);
        
        // Actualizamos el contenido del elemento con el prefijo
        element.textContent = prefix + formatCurrency(currentValue); // Aplicar el formato de moneda después del conteo

        // Si no hemos alcanzado el valor final, continuamos la animación
        if (currentValue < end) {
            requestAnimationFrame(animateCount);
        }
    }

    // Iniciar la animación
    requestAnimationFrame(animateCount);
}


    });
</script>