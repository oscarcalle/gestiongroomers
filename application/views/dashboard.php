<main class="page-content">
    <div class="container-fluid">
        
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
    <li class="breadcrumb-item"><i class="fa fa-tachometer-alt"></i> Control Panel</li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard Gerencia</li>
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

                                <div class="selectedpicker mt-3">
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
                                    <button id="reporteGeneral" class="btn btn-success" style="height: 41px;"><i class="fa fa-file-excel"></i> Reporte</button>
                                    
                                </div>
                </div>
            </div>
        </div>


        <!-- Content Row 1 -->
        <div class="row mt-3 mt-sm-5">
            <!-- Ventas Card -->
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-primary  h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-center">
                            <div class="col-auto ml-5">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-primary text-uppercase mb-1">Ventas</div>
                                <div id="ventas-2024"class="fs-4">S/0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transacciones Card -->
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-success  h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-success text-uppercase mb-1">Transacciones
                                </div>
                                <div id="transacciones-2024"class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promedio Card -->
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-info  h-100">
                    <div class="card-body" title="Ticket Promedio = Suma Ventas / Clientes" data-toggle="tooltip"
                        tooltip>
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-receipt fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-info text-uppercase mb-1">Ticket Promedio
                                </div>
                                <div id="promedio-2024"class="fs-4">S/0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes Card -->
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-warning  h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-auto ml-5">
                                <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                            </div>
                            <div class="col text-center">
                                <div class="font-weight-bold text-warning text-uppercase mb-1">Clientes</div>
                                <div id="clientes-2024"class="fs-4">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <!-- Primera Card -->
            <div class="col-lg-4 mb-2">
                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Ingreso por Área</h5>
                        <div class="container">
                            <!-- Contenido de la primera card -->
                            <div class="row">
                                <center>
                            <canvas id="myPieChart" class="myChart" style="height: 350px;width:350px"></canvas>
                            </center>

                                <div class="table-responsive">
                                <table class="table">
                                    <thead style="line-height:5;">
                                        <tr>
                                            <th>Sede</th>
                                            <th>Ventas(S/)</th>
                                            <th>Transacciones</th>
                                            <th>Clientes</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody id="salesTableBody"></tbody>
                                    <tfoot id="salesTableFoot"></tfoot>
                                </table>
                                </div>

                                * Informacion actualizada al 
                                <?php
                                date_default_timezone_set('America/Lima');
                                echo date('d/m/Y H:i:s');
                                ?>

                                <button id="reporteVentasDetalladasButton" class="btn btn-success w-100 mt-3">
                                <i class="fa fa-download"></i> Descargar Detalles
                            </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segunda Card -->
            <div class="col-lg-8 mb-2">
                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Estructura de la Venta</h5>

                        <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td rowspan="4" colspan="2"
                                    style="vertical-align: middle; text-align: center;background:#eff7ff" class="estilo1">
                                    <div id="total_ventas" class="fs-4">0</div> <span class="font-weight-bold">VENTAS</span> <i class="fas fa-shopping-cart"></i>
                                </td>
                                <td rowspan="2" style="vertical-align: middle; text-align: center;background:#fef1f6"  class="estilo2">
                                    <div id="total_clientes" class="fs-4">0</div> <span class="font-weight-bold">CLIENTES</span> <i class="fas fa-user-friends"></i>
                                </td>
                                <td class="estilo3" style="background:#f9e79f">
                                    <div id="clientes_nuevos" class="fs-4">0</div> <span class="font-weight-bold">NUEVOS</span> <i class="fas fa-user-plus"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="estilo4" style="background:#f2dede">
                                    <div id="clientes_antiguos" class="fs-4">0</div> <span class="font-weight-bold">CLIENTES</span> <i class="fas fa-user-check"></i>
                                </td>
                            </tr>
                            <!-- <tr>
                                <td class="estilo5" style="background:#dff0d8">
                                    <div id="clientes_recuperacion" class="fs-4">0</div> <span class="font-weight-bold">RECUPERACIÓN</span> <i class="fas fa-user-clock"></i>
                                </td>
                            </tr> -->
                            <tr>
                                <td rowspan="2" style="vertical-align: middle; text-align: center;background:#ffecc6" class="estilo6" title="Ticket Promedio = Suma Ventas / Clientes"  data-toggle="tooltip" tooltip>
                                    <div id="ticket_promedio" class="fs-4">0</div> <span class="font-weight-bold">TICKET PROMEDIO</span> <i class="fas fa-receipt"></i>
                                </td>
                                <td class="estilo7" style="background:#fcf8e3">
                                    <div id="cant_serv_prod" class="fs-4">0</div> <span class="font-weight-bold">CANT SERVICIOS/PRODUCTOS</span> <i class="fas fa-boxes"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="estilo8" style="background:#d9edf7">
                                    <div id="precio_promedio" class="fs-4">0</div> <span class="font-weight-bold">PRECIO PROMEDIO</span> <i class="fas fa-dollar-sign"></i>
                                </td>
                            </tr>
                        </table>
                        </div>
                    </div>
                </div>

                <div class="card ">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <h6>Distribución de Servicios/Productos por Área</h6>
                                <canvas id="graficoPieServiciosProductos" style="height: 350px"></canvas>
                            </div>
                            <div class="col-12 col-md-6">
                                <h6>Total Facturado por Área (S/)</h6>
                                <canvas id="graficoPieTotalConIGV" style="height: 350px;"></canvas>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>




        <div class="row">
            <div class="col">
                <div class="card  mb-4">
                    <div class="card-body">

                    <h5 class="card-title pb-2">Servicios y Productos</h5>

                        <!-- Tabla -->
                        <div class="table-responsive">
                            <table class="table" role="table"
                                aria-label="Ventas por Área y Categoría">
                                <!-- Encabezado de tabla -->
                                <thead>
                                    <tr>
                                        <th scope="col">Área</th>
                                        <th scope="col">Categoría</th>
                                        <th scope="col">Benavides</th>
                                        <th scope="col">Jorge Chavez</th>
                                        <th scope="col">San Borja</th>
                                        <th scope="col">La Molina</th>
                                        <th scope="col">Magdalena</th>
                                        <th scope="col">Pet Movil</th>
                                        <th scope="col">Total General</th>
                                    </tr>
                                </thead>

                                <!-- Cuerpo de tabla -->
                                <tbody id="tablaDatos">
                                    <!-- Los datos se insertarán aquí con JavaScript -->
                                </tbody>

                                <!-- Pie de tabla -->
                                <tfoot id="tablaTotales">
                                    <!-- Los totales se insertarán aquí con JavaScript -->
                                </tfoot>
                            </table>
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
function fetchDataAndProcess(url, fetchData, onSuccess, onError) {
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(fetchData)
        })
        .then(response => response.ok ? response.json() : Promise.reject(`Error: ${response.statusText}`))
        .then(data => {
            onSuccess(data);
            resolve(data);  // Resolvemos la promesa si la respuesta es exitosa
        })
        .catch(error => {
            console.error(`Error en la llamada fetch a ${url}:`, error);
            if (onError) onError(error);
            reject(error);  // Rechazamos la promesa si ocurre un error
        });
    });
}


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
        document.getElementById('clientes_antiguos').textContent = data[0].ClientesAntiguos;
    });

    fetchDataAndProcess('./ventascontroller/area_categoria', fetchData, llenarTabla);

    // fetchDataAndProcess('./ventascontroller/area_categoria_nombre', fetchData, data => {
    //     console.log(data);
    // });

    // fetchDataAndProcess('./ventascontroller/total_ventas_por_area', fetchData, data => {
    //     console.log(data);
    // });

    // fetchDataAndProcess('./ventascontroller/total_planes_salud_vendidos', fetchData, data => {
    //     console.log(data);
    // });

    // fetchDataAndProcess('./ventascontroller/total_servicios_vendidos', fetchData, data => {
    //     console.log(data);
    // });



    fetchDataAndProcess('./ventascontroller/obtenerVentas', fetchData, data => {
        // Procesar las ventas por sede
        renderChartAndTable(data.ventasPorSede);

        // Extraer y procesar los datos de servicios y productos
        const serviciosProductos = data.serviciosProductos[0];
        if (serviciosProductos) {
            document.getElementById('cant_serv_prod').textContent = `${parseInt(serviciosProductos.CantidadServicios)} / ${parseInt(serviciosProductos.CantidadProductos)}`;
            document.getElementById('precio_promedio').textContent = "S/ " + formatCurrency(parseFloat(serviciosProductos.precio_promedio));
        } else {
            toastr.error('No hay datos de servicios y productos para el rango de fechas seleccionado.');
        }
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
    console.log(fetchData);
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
                responsive: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
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
        const tableBodyFoot = document.getElementById('salesTableFoot');
        tableBody.innerHTML = ''; // Limpiar el cuerpo de la tabla antes de renderizar
        tableBodyFoot.innerHTML = ''; // Limpiar el pie de tabla antes de renderizar

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

            const row = `
      <tr>
        <td>${item.sede}</td>
        <td>${item.SumaVentas}</td>
        <td>${item.TotalVentas}</td>
        <td>${item.TotalClientesUnicos}</td>
        <td>${porcentaje}%</td>
      </tr>
    `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });

        // Crear la fila de sumas
        const totalRow = `
    <tr>
      <td><strong>Total</strong></td>
      <td><strong>${formatCurrency(totalVentasSum)}</strong></td>
      <td><strong>${formatCurrency(totalVentas)}</strong></td>
      <td><strong>${formatCurrency(totalClientesUnicos)}</strong></td>
      <td><strong>${((totalVentasSum / totalVentasGlobal) * 100).toFixed(2)}%</strong></td>
    </tr>
  `;
        tableBodyFoot.insertAdjacentHTML('beforeend', totalRow);


         // Actualizar los totales con efecto de conteo progresivo
    countUp('ventas-2024', 0, totalVentasSum, 2000,  "S/. ");  // 2000ms para completar el conteo
    countUp('transacciones-2024', 0, totalVentas, 2000);
    countUp('promedio-2024', 0, totalTicketPromedio, 2000,  "S/. ");
    countUp('clientes-2024', 0, totalClientesUnicos, 2000);

        document.getElementById('total_ventas').textContent = "S/" + formatCurrency(totalVentasSum);
        document.getElementById('ticket_promedio').textContent = "S/" + formatCurrency(totalTicketPromedio);
        document.getElementById('total_clientes').textContent = formatCurrency(totalClientesUnicos);
}

function llenarTabla(data) {
        const tbody = document.getElementById('tablaDatos');
        tbody.innerHTML = ''; // Limpiar tabla antes de llenarla

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
            tbody.appendChild(row);
        });

        // Agregar pie de tabla (tfoot)
        const tfoot = document.getElementById('tablaTotales');
        tfoot.innerHTML = `
        <tr>
            <th colspan="2">Total</th>
            <th>${totalBenavides}</th>
            <th>${totalJorgeChavez}</th>
            <th>${totalSanBorja}</th>
            <th>${totalLaMolina}</th>
            <th>${totalMagdalena}</th>
            <th>${totalPetMovil}</th>
            <th>${totalGeneral}</th>
        </tr>
    `;

        // Gráficos
        const ctxServiciosProductos = document.getElementById('graficoPieServiciosProductos').getContext('2d');
        const ctxTotalConIGV = document.getElementById('graficoPieTotalConIGV').getContext('2d');

        if (window.chartServiciosProductos) {
            window.chartServiciosProductos.destroy(); // Destruir gráfico anterior si existe
        }
        if (window.chartTotalConIGV) {
            window.chartTotalConIGV.destroy(); // Destruir gráfico anterior si existe
        }

        // Colores predefinidos por orden
        const predefinedColors = ["#538AC0", "#96F3DB", "#EAFEE6", "#FFDDC1", "#F4B6C2", "#D4A5A5", "#B38A58"];

        // Datos para gráficos
        const areaSumaServiciosProductos = {};
        const areaSumaTotalConIGV = {};

        const areas = []; // Para mantener el orden de aparición

        // Generar datos
        data.forEach(item => {
            const area = item.AREA || 'OTROS';
            if (!areas.includes(area)) areas.push(area); // Agregar al arreglo de áreas si no está

            const totalServiciosProductos = parseInt(item.TotalGeneral_ServiciosProductos || 0);
            const totalConIGV = parseFloat(item.TotalGeneral_TotalConIGV || 0);

            areaSumaServiciosProductos[area] = (areaSumaServiciosProductos[area] || 0) + totalServiciosProductos;
            areaSumaTotalConIGV[area] = (areaSumaTotalConIGV[area] || 0) + totalConIGV;
        });

        const valoresServiciosProductos = areas.map(area => areaSumaServiciosProductos[area]);
        const valoresTotalConIGV = areas.map(area => areaSumaTotalConIGV[area]);
        const colors = areas.map((_, index) => predefinedColors[index % predefinedColors.length]); // Asignar colores por índice

        // Crear gráfico de pie para servicios/productos
        window.chartServiciosProductos = new Chart(ctxServiciosProductos, {
            type: 'pie',
            data: {
                labels: areas,
                datasets: [{
                    data: valoresServiciosProductos,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                const valor = tooltipItem.raw;
                                return `${valor} servicios/productos`;
                            }
                        }
                    }
                }
            }
        });

        // Crear gráfico de pie para TotalConIGV
        window.chartTotalConIGV = new Chart(ctxTotalConIGV, {
            type: 'pie',
            data: {
                labels: areas,
                datasets: [{
                    data: valoresTotalConIGV,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                const valor = tooltipItem.raw.toFixed(2);
                                return `S/ ${valor}`;
                            }
                        }
                    }
                }
            }
        });
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