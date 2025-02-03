<main class="page-content">
    <div class="container-fluid">
        
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
    <li class="breadcrumb-item"><i class="fa fa-tachometer-alt"></i> Control Panel</li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard Mascotas</li>
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
            <div class="col-xl-2 col-md-6 mb-2">
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
            <div class="col-xl-2 col-md-6 mb-2">
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
                                <div id="clientes_recuperacion" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Clientes Card -->
            <div class="col-xl-2 col-md-6 mb-2">
                <div class="card border-left-secondary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-secondary text-uppercase mb-1">Total Clientes</div>
                                <div id="clientes_total" class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        </div>




        <div class="row">
            <!-- Primera Card -->
            <div class="col-lg-6 mb-2">

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad por Especie/Mascota</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->

                        </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad por Tipo</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->

                        </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Top Vendedores PS</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->

                        </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad y Motivo NO Venta PS</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->

                        </div>
                    </div>
                </div>

            </div>

            <!-- Segunda Card -->
            <div class="col-lg-6 mb-2">

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Clientes por Distrito</h5>
                    <div class="container">
                            <!-- Contenido de la primera card -->
                      </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Recuento de Cantidades por Mes y Sede</h5>
                    <div class="container">
                            <!-- Contenido de la primera card -->
                      </div>
                    </div>
                </div>

                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Venta por tipo PS</h5>
                      <div class="container">
                            <!-- Contenido de la primera card -->
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


let datosCargados = []; // Variable global para almacenar los datos
let datosCargados2 = []; // Variable global para almacenar los datos

let miGraficoChart; // Variable global para almacenar el gráfico

// Función principal para inicializar estadísticas
function initEstadisticas(start, end) {
    const fetchData = getFetchData();

     // Validar antes de continuar
     if (!validarSeleccion(fetchData)) {
        return;
    }
    
    toggleLoader(true);



// Definir las rutas según las empresas seleccionadas
const routes = [];
if (fetchData.empresas.includes('petmax')) {
    routes.push({ url: './ventascontroller/area_categoria', name: 'Petmax' });
}
if (fetchData.empresas.includes('gosac')) {
    routes.push({ url: './gosaccontroller/area_categoria_gosac', name: 'Gosac' });
}

// Ejecutar todas las solicitudes de forma dinámica
Promise.all(
    routes.map(route => fetchDataAndProcess(route.url, fetchData))
).then(results => {
    // Filtrar resultados nulos o vacíos
    const validResults = results.filter(data => data && data.length > 0);

    if (validResults.length === 0) {
        toastr.error('No se encontraron datos de área categoría.');
        return;
    }

    // Combinar todos los datos válidos
    datosCargados = validResults.flat();

    // Llenar la tabla con los datos combinados
    llenarTabla(datosCargados);
    llenarSelectAreas(datosCargados);
    const areaPorDefecto = "PELUQUERIA"; // Área por defecto
    filtrarPorArea(areaPorDefecto, datosCargados); // Filtrar automáticamente
}).catch(error => {
    toastr.error('Error al obtener datos de área categoría.');
    console.error('Error capturado:', error);
});


const routes2 = [];
if (fetchData.empresas.includes('petmax')) {
    routes2.push({ url: './ventascontroller/area_categoria_nombre', name: 'Petmax' });
}
if (fetchData.empresas.includes('gosac')) {
    routes2.push({ url: './gosaccontroller/area_categoria_nombre_gosac', name: 'Gosac' });
}

// Ejecutar todas las solicitudes de forma dinámica
Promise.all(
    routes2.map(route => fetchDataAndProcess(route.url, fetchData))
).then(results => {
    // Filtrar resultados nulos o vacíos
    const validResults = results.filter(data => data && data.length > 0);

    if (validResults.length === 0) {
        toastr.error('No se encontraron datos de área categoría.');
        return;
    }

    // Combinar todos los datos válidos
    datosCargados2 = validResults.flat();

    // Llenar la tabla con los datos combinados
    llenarTabla2(datosCargados2);
    llenarSelectAreas2(datosCargados2);
    const areaPorDefecto2 = "MEDICA"; // Área por defecto
    filtrarPorArea2(areaPorDefecto2, datosCargados2); // Filtrar automáticament
}).catch(error => {
    toastr.error('Error al obtener datos de área categoría.');
    console.error('Error capturado:', error);
});

    fetchDataAndProcess('./dashboard/get_clientes', fetchData, data => {
        document.getElementById('clientes_nuevos').textContent = data[0].ClientesNuevos;
        document.getElementById('clientes_constantes').textContent = data[0].ClientesFrecuentes;
        document.getElementById('clientes_recuperacion').textContent = data[0].ClientesARecuperar;
    });


    //fetchDataAndProcess('./ventascontroller/total_planes_salud_vendidos', fetchData, renderChartCantidadPlanesSalud);

    fetchDataAndProcess('./ventascontroller/total_planes_salud_vendidos', fetchData, data => {
        if(data === null) {
            toastr.error('No hay datos de planes de salud para el rango de fechas seleccionado.');
    
            // Destruir el gráfico existente si ya fue creado
            if (typeof miGraficoChart !== 'undefined' && miGraficoChart) {
                miGraficoChart.destroy();
                miGraficoChart = null; // Asegurarse de que la referencia sea nula
            }
            document.getElementById('txtmiGrafico').innerHTML = '<p>Aún no hay Ventas de Planes de Salud</p>';
            return;
        }
        document.getElementById('txtmiGrafico').innerHTML = '';
        renderChartCantidadPlanesSalud(data);
    });

    fetchDataAndProcess('./ventascontroller/total_consultas_vendidos', fetchData, tablaConsultas);

    fetchDataAndProcess('./ventascontroller/meta_mes', fetchData, (data, error) => {
    const metaTable = document.getElementById('metaTable');
    const progressContainer = document.getElementById('progressContainer');
    const toggleTable = document.getElementById('toggleTable');
    const toggleLabel = document.getElementById('toggleLabel');

    // Limpiar contenido previo
    progressContainer.innerHTML = '';
    metaTable.style.display = 'none'; // Ocultar tabla por defecto
    toggleTable.checked = false; // Desmarcar el checkbox

    if (error) {
        // Si hay un error, muestra el mensaje correspondiente
        progressContainer.innerHTML = `
            <div class="alert alert-warning text-center">
                ${error === 'Error de red' ? 'Hubo un problema al cargar los datos.' : 'No hay metas asignadas en el mes.'}
            </div>
        `;
        toggleLabel.style.display = 'none'; // Ocultar el checkbox si hay un error
        toggleTable.style.display = 'none'; // Ocultar el checkbox si hay un error
        return;
    }

    if (!data || (Array.isArray(data) && data.length === 0)) {
        // Si los datos están vacíos, muestra el mensaje correspondiente
        progressContainer.innerHTML = `
            <div class="alert alert-warning text-center">
                No hay metas asignadas en el mes
            </div>
        `;
        toggleLabel.style.display = 'none'; // Ocultar el checkbox si no hay metas
        toggleTable.style.display = 'none'; // Ocultar el checkbox si no hay metas
        return;
    }

    // Mostrar la tabla y llenar los datos
    metaTable.style.display = 'table'; // Mostrar tabla
    toggleLabel.style.display = 'block'; // Asegurarse de que el checkbox esté visible
    toggleTable.style.display = 'block'; // Asegurarse de que el checkbox esté visible
    createProgressBar(data); // Llamar a la función para procesar datos
});



    // fetchDataAndProcess('./gosaccontroller/obtenerVentas_gosac', fetchData, data => {
    //     console.log(data);
    // });

    // fetchDataAndProcess('./gosaccontroller/serviciosYprecioPromedio_gosac', fetchData, data => {
    //     console.log(data);
    // });


    fetchDataAndProcess('./ventascontroller/obtenerVentas', fetchData, data => {
        // Almacenar el resultado de ventasPorArea en una variable
        const ventasPorAreaData = data.ventasPorArea;

        // Procesar las ventas por sede
        renderChartAndTable(data.ventasPorSede);

        renderChartCantidadEspecie(data.ventasPorEspecie);

        // Extraer y procesar los datos de servicios y productos
        const serviciosProductos = data.serviciosProductos[0];
        if (serviciosProductos) {
            document.getElementById('cant_serv_prod').textContent = `${parseInt(serviciosProductos.CantidadServicios)} / ${parseInt(serviciosProductos.CantidadProductos)}`;
            document.getElementById('precio_promedio').textContent = "S/ " + formatCurrency(parseFloat(serviciosProductos.precio_promedio));
        } else {
            toastr.error('No hay datos de servicios y productos para el rango de fechas seleccionado.');
        }

        // Crear la variable routes3 dinámica
    const routes3 = [];

    // Si la empresa es 'petmax', se usa los datos de ventasPorArea directamente
    if (fetchData.empresas.includes('petmax') && ventasPorAreaData) {
        routes3.push({ data: ventasPorAreaData, name: 'Petmax' });
    }

    // Si la empresa es 'gosac', se hace una solicitud a la URL
    if (fetchData.empresas.includes('gosac')) {
        routes3.push({ url: './gosaccontroller/total_ventas_por_area_gosac', name: 'Gosac' });
    }

    // Ejecutar todas las solicitudes de forma dinámica
    Promise.all(
        routes3.map(route => {
            // Si ya tenemos los datos, no hacer una solicitud, sino usar los datos directamente
            if (route.data) {
                return Promise.resolve(route.data); // Devolver los datos de ventasPorArea
            } else {
                return fetchDataAndProcess(route.url, fetchData); // Si no, hacer la solicitud
            }
        })
    ).then(results => {
        // Filtrar resultados nulos o vacíos
        const validResults = results.filter(data => data && data.length > 0);

        if (validResults.length === 0) {
            toastr.error('No se encontraron datos de área categoría.');
            return;
        }

        // Combinar todos los datos válidos
        const combinedData = validResults.flat();

        // Llenar la tabla con los datos combinados
        tablaAreas(combinedData);
    }).catch(error => {
        toastr.error('Error al obtener datos de área categoría.');
        console.error('Error capturado:', error);
    });

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

function renderChartCantidadEspecie(data){
    if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                } else {

                    // Procesar datos para Chart.js
                    const sedes = [...new Set(data.map(item => item.sede))]; // Sedes únicas
                    //const especies = ["Perro", "Gato"]; // Especies únicas
                    const especies = [...new Set(data.map(item => item.EspecieMascota))];
                    const colores = { "Perro": "orange", "Gato": "skyblue" }; // Colores por especie

                    // Crear datasets para cada especie
                    const datasets = especies.map(especie => {
                        return {
                            label: especie,
                            data: sedes.map(sede => {
                                const item = data.find(d => d.sede === sede && d.EspecieMascota === especie);
                                return item ? parseFloat(item.TotalVentas) : 0;
                            }),
                            backgroundColor: colores[especie]
                        };
                    });

                    // Verificar si ya existe el gráfico y destruirlo
                    if (ventasChart) {
                        ventasChart.destroy();
                    }

                    // Configurar y renderizar el gráfico
                    const ctx = document.getElementById('ventasChart').getContext('2d');
                    ventasChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: sedes,
                            datasets: datasets
                        },
                        options: {
                            indexAxis: 'y', // Configuración para barra horizontal
                            plugins: {
                                title: {
                                    display: false
                                    //text: 'Ventas por Sede y Especie'
                                },
                                tooltip: {
                                    mode: 'nearest',
                                    intersect: false
                                },
                                legend: {
                                    display: true,
                                    position: 'right'
                                }
                            },
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: true, // Apilado en el eje X
                                    title: {
                                        display: true,
                                        text: 'Cantidad de Ventas'
                                    }
                                },
                                y: {
                                    stacked: true, // Apilado en el eje Y
                                    title: {
                                        display: true,
                                        text: 'Sedes'
                                    }
                                }
                            }
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
    // Obtener el contexto del canvas
    const ctx = document.getElementById('miGrafico').getContext('2d');

    // Filtrar categorías que tienen valores en los datos
    const categoriasConValores = Object.keys(abreviacionesCategorias).filter(categoria =>
        data.some(item => item.Nombre === categoria && item.TotalVenta > 0)
    );

    // Obtener las sedes únicas
    const sedes = [...new Set(data.map(item => item.sede))];

    // Crear datasets por sede
    const datasets = sedes.map((sede, index) => {
        const colores = ["#FF5733", "#FFC300", "#DAF7A6", "#C70039", "#581845", "#28B463"];
        return {
            label: sede, // Nombre de la sede
            backgroundColor: colores[index % colores.length],
            data: categoriasConValores.map(categoria => {
                // Buscar el valor correspondiente para esta sede y categoría
                const item = data.find(d => d.sede === sede && d.Nombre === categoria);
                return item ? item.TotalVenta : 0;
            })
        };
    });

    // Configuración del gráfico
    const config = {
        type: 'bar',
        data: {
            labels: categoriasConValores.map(categoria => abreviacionesCategorias[categoria]), // Usar nombres abreviados
            datasets: datasets
        },
        options: {
            plugins: {
                legend: {
                    position: 'right' // Muestra la leyenda a la derecha
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
                    stacked: false, // Cada sede se representa como una barra separada
                    title: {
                        display: true,
                        text: 'Categorías'
                    }
                },
                y: {
                    stacked: false,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Venta'
                    }
                }
            }
        }
    };

    // Crear un nuevo gráfico
    miGraficoChart = new Chart(ctx, config);
}


function renderChartAndTable(data) {

     // Extraer datos para el gráfico
     const labels = data.map(item => item.sede);
        const ventasTotales = data.map(item => parseFloat(item.SumaVentas));

        // Calcular el total de todas las ventas para el porcentaje
        const totalVentasGlobal = ventasTotales.reduce((total, venta) => total + venta, 0);

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
            totalTicketPromedio = totalVentasSum / totalClientesUnicos;
        });


 // Actualizar los totales con efecto de conteo progresivo
 countUp('ventas-2024', 0, totalVentasSum, 2000,  "S/. ");  // 2000ms para completar el conteo
    countUp('transacciones-2024', 0, totalVentas, 2000);
    countUp('promedio-2024', 0, totalTicketPromedio, 2000,  "S/. ");
    countUp('clientes-2024', 0, totalClientesUnicos, 2000);

        document.getElementById('total_ventas').textContent = "S/" + formatCurrency(totalVentasSum);
        document.getElementById('ticket_promedio').textContent = "S/" + formatCurrency(totalTicketPromedio);
        document.getElementById('total_clientes').textContent = formatCurrency(totalClientesUnicos);

}


   const selectArea = document.getElementById('areaFilter');
    const tablaDatos = document.getElementById('tablaDatos');
    const tablaTotales = document.getElementById('tablaTotales');

    // Función para llenar el `select` con áreas únicas
    function llenarSelectAreas(data) {
        const areas = [...new Set(data.map(item => item.AREA || 'OTROS'))];
        selectArea.innerHTML = areas
            .map(area => `<option value="${area}">${area}</option>`)
            .join('');
        selectArea.value = "PELUQUERIA"; // Selecciona "Peluquería" por defecto
    }

    // Función para filtrar los datos por área
    function filtrarPorArea(area, data) {
        const datosFiltrados = data.filter(item => (item.AREA || 'OTROS') === area);
        llenarTabla(datosFiltrados);
    }

    function llenarTabla(data) {
    
        tablaDatos.innerHTML = ''; // Limpiar tabla antes de llenarla
        let totalBenavides = 0, totalJorgeChavez = 0, totalSanBorja = 0,
            totalLaMolina = 0, totalMagdalena = 0, totalPetMovil = 0, totalGeneral = 0;

        data.forEach(item => {
            const row = document.createElement('tr');

            // Sumar los totales
            totalBenavides += parseFloat(item.Benavides_TotalServiciosProductos || 0);
            totalJorgeChavez += parseFloat(item.JorgeChavez_TotalServiciosProductos || 0);
            totalSanBorja += parseFloat(item.SanBorja_TotalServiciosProductos || 0);
            totalLaMolina += parseFloat(item.LaMolina_TotalServiciosProductos || 0);
            totalMagdalena += parseFloat(item.Magdalena_TotalServiciosProductos || 0);
            totalPetMovil += parseFloat(item.PetMovil_TotalServiciosProductos || 0);
            totalGeneral += parseFloat(item.TotalGeneral_ServiciosProductos || 0);

            // Crear celdas de la fila
            row.innerHTML = `
                <td>${item.empresa}</td>
                <td>${item.AREA || 'OTROS'}</td>
                <td>${item.Categoria}</td>
                <td>${item.Benavides_TotalServiciosProductos}</td>
                <td>${item.JorgeChavez_TotalServiciosProductos}</td>
                <td>${item.SanBorja_TotalServiciosProductos}</td>
                <td>${item.LaMolina_TotalServiciosProductos}</td>
                <td>${item.Magdalena_TotalServiciosProductos}</td>
                <td>${item.PetMovil_TotalServiciosProductos}</td>
                <td>${item.TotalGeneral_ServiciosProductos}</td>
            `;

            // Añadir la fila al cuerpo de la tabla
            tablaDatos.appendChild(row);
        });

        // Agregar pie de tabla (tfoot)
        tablaTotales.innerHTML = `
            <tr>
                <th colspan="3">Total</th>
                <th>${totalBenavides}</th>
                <th>${totalJorgeChavez}</th>
                <th>${totalSanBorja}</th>
                <th>${totalLaMolina}</th>
                <th>${totalMagdalena}</th>
                <th>${totalPetMovil}</th>
                <th>${totalGeneral}</th>
            </tr>
        `;
    }

     // Evento al cambiar el filtro
     selectArea.addEventListener('change', () => {
    const areaSeleccionada = selectArea.value;
    filtrarPorArea(areaSeleccionada, datosCargados); // Usar la variable global
});

// Elementos del DOM
const selectArea2 = document.getElementById('areacategoriaFilter');
const tablaDatos2 = document.getElementById('tablaDatos2');
const tablaTotales2 = document.getElementById('tablaTotales2');

// Función para llenar el select con áreas únicas
function llenarSelectAreas2(data) {
    const areas = [...new Set(data.map(item => item.AREA || 'OTROS'))];
    selectArea2.innerHTML = areas
        .map(area => `<option value="${area}">${area}</option>`)
        .join('');
    selectArea2.value = "MEDICA"; // Seleccionar "Peluquería" por defecto
}

// Función para filtrar los datos por área
function filtrarPorArea2(area, data) {
    if (!Array.isArray(data)) {
        console.error('Los datos no son un arreglo:', data);
        return;
    }
    const datosFiltrados2 = data.filter(item => (item.AREA || 'OTROS') === area);
    llenarTabla2(datosFiltrados2);
}

// Función para llenar la tabla con datos
function llenarTabla2(data) {
    tablaDatos2.innerHTML = ''; // Limpiar la tabla antes de llenarla
    let totalBenavides = 0, totalJorgeChavez = 0, totalSanBorja = 0,
        totalLaMolina = 0, totalMagdalena = 0, totalPetMovil = 0, totalGeneral = 0;

    data.forEach(item => {
        const row = document.createElement('tr');

        // Sumar los totales para el pie de tabla
        totalBenavides += parseFloat(item.Benavides_TotalServiciosProductos || 0);
        totalJorgeChavez += parseFloat(item.JorgeChavez_TotalServiciosProductos || 0);
        totalSanBorja += parseFloat(item.SanBorja_TotalServiciosProductos || 0);
        totalLaMolina += parseFloat(item.LaMolina_TotalServiciosProductos || 0);
        totalMagdalena += parseFloat(item.Magdalena_TotalServiciosProductos || 0);
        totalPetMovil += parseFloat(item.PetMovil_TotalServiciosProductos || 0);
        totalGeneral += parseFloat(item.TotalGeneral_ServiciosProductos || 0);

        // Crear celdas de la fila
        row.innerHTML = `
            <td>${item.empresa}</td>
            <td>${item.AREA || 'OTROS'}</td>
            <td>${item.Categoria}</td>
            <td>${item.Nombre}</td>
            <td>${item.Benavides_TotalServiciosProductos}</td>
            <td>${item.JorgeChavez_TotalServiciosProductos}</td>
            <td>${item.SanBorja_TotalServiciosProductos}</td>
            <td>${item.LaMolina_TotalServiciosProductos}</td>
            <td>${item.Magdalena_TotalServiciosProductos}</td>
            <td>${item.PetMovil_TotalServiciosProductos}</td>
            <td>${item.TotalGeneral_ServiciosProductos}</td>
        `;

        // Añadir la fila al cuerpo de la tabla
        tablaDatos2.appendChild(row);
    });

    // Agregar pie de tabla (tfoot)
    tablaTotales2.innerHTML = `
        <tr>
            <th colspan="4">Total</th>
            <th>${totalBenavides}</th>
            <th>${totalJorgeChavez}</th>
            <th>${totalSanBorja}</th>
            <th>${totalLaMolina}</th>
            <th>${totalMagdalena}</th>
            <th>${totalPetMovil}</th>
            <th>${totalGeneral}</th>
        </tr>
    `;
}

// Evento al cambiar el filtro
selectArea2.addEventListener('change', () => {
    const areaSeleccionada2 = selectArea2.value;
    filtrarPorArea2(areaSeleccionada2, datosCargados2); // Usar la variable global
});


function tablaConsultas(data) {
    const tabla = document.getElementById('tablaConsultas');  // Suponiendo que hay un elemento con id 'tabla'
    tabla.innerHTML = '';  // Limpiar tabla antes de agregar nuevos datos

    // Paso 1: Crear un conjunto de todos los servicios únicos
    const servicios = [...new Set(data.map(item => item.Servicio))];

    // Paso 2: Crear las cabeceras de la tabla
    let headerRow = '<tr><th>Sedes</th>';
    servicios.forEach(servicio => {
        headerRow += `<th>${servicio}</th>`;
    });
    headerRow += '<th>Total</th>'; // Agregar la cabecera para la columna de sumatoria
    headerRow += '</tr>';

    // Paso 3: Crear un objeto para almacenar las cantidades por sede y servicio
    const sedeServicios = {};

    // Inicializar el objeto sedeServicios con las sedes y los servicios como claves
    data.forEach(item => {
        if (!sedeServicios[item.sede]) {
            sedeServicios[item.sede] = {};
        }
        sedeServicios[item.sede][item.Servicio] = item.TotalServiciosVendidos;
    });

    // Paso 4: Crear las filas de la tabla y calcular la sumatoria por sede
    let rows = '';
    let sumatorias = Array(servicios.length).fill(0); // Array para guardar las sumatorias por servicio

    Object.keys(sedeServicios).forEach(sede => {
        let row = `<tr><td>${sede}</td>`; // Empezamos la fila con el nombre de la sede
        let totalSede = 0; // Variable para la sumatoria de la sede

        servicios.forEach((servicio, index) => {
            const cantidad = sedeServicios[sede][servicio] || 0;
            row += `<td>${cantidad}</td>`;
            totalSede += parseFloat(cantidad);  // Sumar la cantidad al total de la sede
            sumatorias[index] += parseFloat(cantidad); // Sumar la cantidad al total general de ese servicio
        });

        row += `<td>${formatCurrency(totalSede)}</td>`; // Mostrar la sumatoria de la sede en la última columna
        row += '</tr>';
        rows += row;
    });

    // Paso 5: Crear la fila de sumatorias en el tfoot
    let footerRow = '<tr><td><strong>Total</strong></td>';
    sumatorias.forEach(sumatoria => {
        footerRow += `<td><strong>${formatCurrency(sumatoria)}</strong></td>`; // Mostrar la sumatoria por servicio
    });
    const totalGeneral = sumatorias.reduce((acc, curr) => acc + curr, 0); // Sumar todas las sumas para obtener el total general
    footerRow += `<td><strong>${formatCurrency(totalGeneral)}</strong></td>`; // Mostrar el total general en la última columna
    footerRow += '</tr>';

    // Paso 6: Construir la tabla final
    tabla.innerHTML = `<table><thead>${headerRow}</thead><tbody>${rows}</tbody><tfoot>${footerRow}</tfoot></table>`;
}

function tablaAreas(data) {

    if (!Array.isArray(data) || data.length === 0) {
        console.error("Los datos proporcionados están vacíos o no son un arreglo.");
        return;
    }

    const tabla = document.getElementById('tablaAreas');
    tabla.innerHTML = ''; // Limpiar tabla antes de agregar nuevos datos

    // Paso 1: Identificar todas las áreas únicas y empresas únicas
    const empresas = [...new Set(data.map(item => item.empresa))]; // Empresas únicas
    //console.log("Empresas detectadas:", empresas);
    const areas = [...new Set(data.map(item => {
        if (!item || typeof item !== 'object') {
            console.error("Elemento inválido detectado:", item);
            return null;
        }
        return item.AREA;
    }).filter(Boolean))]; // Filtrar valores nulos

    //console.log("Áreas detectadas:", areas);

    // Paso 2: Crear las cabeceras de la tabla
    let headerRow = '<tr><th>Descripción</th>';
    areas.forEach(area => {
        headerRow += `<th>${area}</th>`;
    });
    headerRow += '<th>Total general</th>'; // Agregar columna "Total general"
    headerRow += '</tr>';

    // Crear el encabezado
    tabla.innerHTML = `<thead>${headerRow}</thead><tbody id="tablaAreasBody"></tbody>`;
    const tableBody = document.getElementById('tablaAreasBody');

    // Paso 3: Crear filas para cada empresa
    let totalPorArea = {};
    let totalGeneral = 0;

    empresas.forEach(empresa => {
        const datosEmpresa = data.filter(item => item.empresa === empresa);
        let row = `<tr><td>${empresa.toUpperCase()}</td>`;
        let sumatoriaTotal = 0;

        areas.forEach(area => {
            const areaData = datosEmpresa.find(item => item.AREA === area);
            const totalVentas = areaData ? parseFloat(areaData.TotalVentas || areaData.total_ventas) : 0;

            row += `<td>${formatCurrency(totalVentas)}</td>`;
            sumatoriaTotal += totalVentas;

            // Acumular totales por área
            totalPorArea[area] = (totalPorArea[area] || 0) + totalVentas;
        });

        row += `<td>${formatCurrency(sumatoriaTotal)}</td></tr>`;
        tableBody.innerHTML += row;

        totalGeneral += sumatoriaTotal;
    });

    // Agregar fila de totales por área
    let totalRow = '<tr style="background-color: #EFF7FF; font-weight: bold; color: #000;"><td>Total general</td>';
    areas.forEach(area => {
        totalRow += `<td>${formatCurrency(totalPorArea[area]) || 0}</td>`;
    });
    totalRow += `<td>${formatCurrency(totalGeneral)}</td></tr>`;
    tableBody.innerHTML += totalRow;

    // Agregar fila de "% Sobre Venta"
    let porcentajeRow = '<tr><td>% Sobre Venta</td>';
    areas.forEach(area => {
        const porcentaje = totalPorArea[area] ? (totalPorArea[area] / totalGeneral) * 100 : 0;
        porcentajeRow += `<td>${porcentaje.toFixed(0)}%</td>`;
    });
    porcentajeRow += '<td>100%</td></tr>';
    tableBody.innerHTML += porcentajeRow;

    // Inputs para Días Transcurridos y Días Laborales
    const diasTranscurridosInput = document.getElementById('diasTranscurridos');
    const diasLaboralesInput = document.getElementById('diasLaborales');

    // Función para calcular y mostrar las proyecciones
    function updateProyEmpresa() {
        const diasTranscurridos = parseFloat(diasTranscurridosInput.value);
        const diasLaborales = parseFloat(diasLaboralesInput.value);

        if (isNaN(diasTranscurridos) || diasTranscurridos <= 0 || isNaN(diasLaborales) || diasLaborales <= 0) {
            alert("Por favor ingresa valores válidos para los días.");
            return;
        }

        // Eliminar filas anteriores de proyección
        document.querySelectorAll('.proyRow').forEach(row => row.remove());
        const proyeccionTotalesPorArea = {}; // Acumular proyecciones totales por área
        let proyeccionTotalGeneral = 0; // Acumular el total general de todas las proyecciones

        // Calcular y mostrar proyecciones por empresa
        empresas.forEach(empresa => {
            const datosEmpresa = data.filter(item => item.empresa === empresa);
            let row = `<tr class="proyRow"><td>PROY. ${empresa.toUpperCase()}</td>`;
            let totalProyeccion = 0;

            areas.forEach(area => {
                const areaData = datosEmpresa.find(item => item.AREA === area);
                const totalVentas = areaData ? parseFloat(areaData.TotalVentas || areaData.total_ventas) : 0;
                const proyeccion = (totalVentas / diasTranscurridos) * diasLaborales;
                row += `<td>${formatCurrency(proyeccion)}</td>`;
                totalProyeccion += proyeccion;

                // Sumar al total de proyecciones por área
                proyeccionTotalesPorArea[area] = (proyeccionTotalesPorArea[area] || 0) + proyeccion;
            });

            row += `<td>${formatCurrency(totalProyeccion)}</td></tr>`;
            tableBody.innerHTML += row;

            // Acumular el total general de todas las proyecciones
            proyeccionTotalGeneral += totalProyeccion;
        });

        // Agregar fila de proyección total por área
        //let proyTotalRow = '<tr class="proyRow rowFinal"><td>TOTALES PROYECCIÓN</td>';
        let proyTotalRow = '<tr class="proyRow rowFinal" style="background-color: #EFF7FF; font-weight: bold; color: #000;"><td>TOTALES PROYECCIÓN</td>';

        areas.forEach(area => {
            proyTotalRow += `<td>${(proyeccionTotalesPorArea[area] || 0).toFixed(2)}</td>`;
        });
        proyTotalRow += `<td>${proyeccionTotalGeneral.toFixed(2)}</td></tr>`;
        tableBody.innerHTML += proyTotalRow;
    }

    // Mostrar las proyecciones desde el inicio
    updateProyEmpresa();

    // Escuchar eventos de cambio en los inputs
    diasTranscurridosInput.addEventListener('input', updateProyEmpresa);
    diasLaboralesInput.addEventListener('input', updateProyEmpresa);
}

function createProgressBar(data) {
    const tableBody = document.getElementById('tableBody');
    const progressContainer = document.getElementById('progressContainer');

    // Limpiar contenido previo
    tableBody.innerHTML = '';
    progressContainer.innerHTML = '';

    // Verificar si la respuesta contiene un error
    if (data.error) {
        // Mostrar mensaje en lugar de la tabla
        const messageRow = document.createElement('tr');
        messageRow.innerHTML = `
            <td colspan="5" class="text-center">No hay metas asignadas en el mes</td>
        `;
        tableBody.appendChild(messageRow);

        // Resetear totales
        document.getElementById('totalMeta').textContent = `Total Meta: 0`;
        document.getElementById('totalAvance').textContent = `Total Avance: 0`;
        return;
    }

    let totalMeta = 0;
    let totalVendido = 0;

    // Recorrer los datos y generar las filas
    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.sede}</td>
            <td>${item.mes}</td>
            <td>${item.anio}</td>
            <td>${item.meta}</td>
            <td>${item.TotalVendido}</td>
        `;
        tableBody.appendChild(row);

        totalMeta += parseFloat(item.meta);
        totalVendido += parseFloat(item.TotalVendido);
    });

    totalMeta = totalMeta * 1000; // Convertir a miles
    // Actualizar el total de la meta en el tfoot
    document.getElementById('totalMeta').textContent = `Total Meta:  ${formatCurrency(totalMeta)}`;

    // Calcular el avance actual en porcentaje
    let porcentajeAvance = (totalVendido / (totalMeta)) * 100;

    document.getElementById('totalAvance').textContent = `Total Avance: ${formatCurrency(totalVendido)}`;

    // Limitar el porcentaje a un rango de 0 a 100
    if (porcentajeAvance < 0) porcentajeAvance = 0;
    if (porcentajeAvance > 100) porcentajeAvance = 100;

    // Crear el contenedor de la barra de progreso
    const progressBarContainer = document.createElement('div');
    progressBarContainer.classList.add('progress');

    // Crear la barra de progreso con las clases adicionales
    const progressBar = document.createElement('div');
    progressBar.classList.add('progress-bar', 'progress-bar-striped', 'progress-bar-animated');
    progressBar.style.width = `${porcentajeAvance}%`;
    progressBar.textContent = `${porcentajeAvance.toFixed(2)}%`;

    // Agregar la barra a su contenedor
    progressBarContainer.appendChild(progressBar);
    progressContainer.appendChild(progressBarContainer);
}

function formatCurrency(number) {
    // Convertir el número en formato de moneda
    return number.toFixed(2) // Asegura 2 decimales
        .replace(/\B(?=(\d{3})+(?!\d))/g, "'"); // Agrega el separador de miles (')
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