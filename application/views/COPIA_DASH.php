<main class="page-content">
    <div class="container-fluid">
        
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
    <li class="breadcrumb-item"><i class="fa fa-tachometer-alt"></i> Control Panel</li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard Adm. Agencia</li>
  </ol>
</nav>

        <div class="row mt-0">
            <div class="col-md-12">

                    <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch gap-2">

                                <div  class="selectedpicker mt-3">
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
                                <div id="transacciones-2024"class="fs-4">S/0</div>
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
                                <div id="clientes-2024"class="fs-4">S/0</div>
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
                                
                                    <div class="table-responsive" >
                                        <table class="table" id="tablaAreas"></table>
                                    </div>

                                    <!-- Input para ingresar los días transcurridos -->
                                    <div>
                                        <label for="diasTranscurridos">Días Transcurridos:</label>
                                        <input type="number" id="diasTranscurridos" placeholder="Ingrese los días" value="27" style="width:50px" />
                                    </div>

                                    <!-- Input para ingresar los días laborales -->
                                    <div>
                                        <label for="diasLaborales">Días Laborales:</label>
                                        <input type="number" id="diasLaborales" placeholder="Ingrese los días laborales"
                                            value="30" style="width:50px" />
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segunda Card -->
            <div class="col-lg-4 mb-2">
                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Cantidad por Especie</h5>
                    <canvas id="ventasChart" width="800" height="400"></canvas>
                        
                    </div>
                </div>
            </div>

            <!-- Segunda Card -->
            <div class="col-lg-4 mb-2">
                <div class="card  mb-2">
                    <div class="card-body">
                    <h5 class="card-title pb-2">Estructura de la Venta</h5>

                        <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td rowspan="5" colspan="2"
                                    style="vertical-align: middle; text-align: center;background:#eff7ff" class="estilo1">
                                    <div id="total_ventas" class="fs-4">0</div> <span class="font-weight-bold">VENTAS</span> <i class="fas fa-shopping-cart"></i>
                                </td>
                                <td rowspan="3" style="vertical-align: middle; text-align: center;background:#fef1f6"  class="estilo2">
                                    <div id="total_clientes" class="fs-4">0</div> <span class="font-weight-bold">CLIENTES</span> <i class="fas fa-user-friends"></i>
                                </td>
                                <td class="estilo3" style="background:#f9e79f">
                                    <div id="clientes_nuevos" class="fs-4">0</div> <span class="font-weight-bold">NUEVOS</span> <i class="fas fa-user-plus"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="estilo4" style="background:#f2dede">
                                    <div id="clientes_constantes" class="fs-4">0</div> <span class="font-weight-bold">CONSTANTES</span> <i class="fas fa-user-check"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="estilo5" style="background:#dff0d8">
                                    <div id="clientes_recuperacion" class="fs-4">0</div> <span class="font-weight-bold">RECUPERACIÓN</span> <i class="fas fa-user-clock"></i>
                                </td>
                            </tr>
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
            </div>

        </div>



        <div class="row">

            <div class="col-lg-6 mb-3">
                <div class="card shadow mb-3">
                    <div class="card-body">

                    <h5 class="card-title pb-2">Cantidad por Tipos de Consulta</h5>

                    <div class="table-responsive" >
                    <table class="table" id="tablaConsultas"></table>
                    </div>


                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card shadow mb-3">
                    <div class="card-header py-3">
                    <div class="card-body">
                        <h5 class="card-title pb-2">Cantidad por Plan Salud</h5>
                        <span id="txtmiGrafico"></span>
                        <canvas id="miGrafico" width="800" height="400"></canvas>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col">
                <div class="card  mb-4">
                    <div class="card-body">

                    <h5 class="card-title pb-2">Areas y Categorias</h5>

                        <!-- Tabla -->
                        <div class="table-responsive">
                            <table class="table table-striped" role="table"
                                aria-label="Ventas por Área y Categoría">
                                <!-- Encabezado de tabla -->
                                <thead>
                                    <tr>
                                        <th scope="col">Empresa</th>
                                        <th scope="col">Área</th>
                                        <th scope="col">Categoría</th>
                                        <th scope="col">Benavides</th>
                                        <th scope="col">Jorge Chavez</th>
                                        <th scope="col">San Borja</th>
                                        <th scope="col">La Molina</th>
                                        <th scope="col">Magdalena</th>
                                        <th scope="col">Pet Movil</th>
                                        <th scope="col">TOTAL</th>
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


        <div class="row">

            <div class="col-lg-12 mb-3">
                <div class="card shadow mb-3">
                <div class="card-body">

                <h5 class="card-title pb-2">Areas, Categorias y Servicios</h5>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-striped" role="table" aria-label="Ventas por Área y Categoría">
                            <!-- Encabezado de tabla -->
                            <thead>
                                <tr>
                                    <th scope="col">Empresa</th>
                                    <th scope="col">Área</th>
                                    <th scope="col">Categoría</th>
                                    <th scope="col">Servicio</th>
                                    <th scope="col">Benavides</th>
                                    <th scope="col">Jorge Chavez</th>
                                    <th scope="col">San Borja</th>
                                    <th scope="col">La Molina</th>
                                    <th scope="col">Magdalena</th>
                                    <th scope="col">Pet Movil</th>
                                    <th scope="col">TOTAL</th>
                                </tr>
                            </thead>

                            <!-- Cuerpo de tabla -->
                            <tbody id="tablaDatos2">
                                <!-- Los datos se insertarán aquí con JavaScript -->
                            </tbody>

                            <!-- Pie de tabla -->
                            <tfoot id="tablaTotales2">
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
            if (data && data.error) {
                console.warn(`Endpoint ${url} devolvió un error esperado:`, data.error);
                return null; // Devolvemos null para errores esperados
            }
            // Llamamos a la función de éxito si se define
            if (onSuccess) onSuccess(data);
            return data;
        })
        .catch(error => {
            console.error(`Error en la llamada fetch a ${url}:`, error);
            throw error; // Rechazamos para errores reales de red
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
    const combinedData = validResults.flat();

    // Llenar la tabla con los datos combinados
    llenarTabla(combinedData);
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
    const combinedData = validResults.flat();

    // Llenar la tabla con los datos combinados
    llenarTabla2(combinedData);
}).catch(error => {
    toastr.error('Error al obtener datos de área categoría.');
    console.error('Error capturado:', error);
});


const routes3 = [];
if (fetchData.empresas.includes('petmax')) {
    routes3.push({ url: './ventascontroller/total_ventas_por_area', name: 'Petmax' });
}
if (fetchData.empresas.includes('gosac')) {
    routes3.push({ url: './gosaccontroller/total_ventas_por_area_gosac', name: 'Gosac' });
}

// Ejecutar todas las solicitudes de forma dinámica
Promise.all(
    routes3.map(route => fetchDataAndProcess(route.url, fetchData))
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




    fetchDataAndProcess('./dashboard/get_clientes', fetchData, data => {
        document.getElementById('clientes_nuevos').textContent = data[0].ClientesNuevos;
        document.getElementById('clientes_constantes').textContent = data[0].ClientesFrecuentes;
        document.getElementById('clientes_recuperacion').textContent = data[0].ClientesARecuperar;
    });

    

    fetchDataAndProcess('./ventascontroller/total_planes_salud_vendidos', fetchData, renderChartCantidadPlanesSalud);

    fetchDataAndProcess('./ventascontroller/total_consultas_vendidos', fetchData, tablaConsultas);

    //fetchDataAndProcess('./ventascontroller/total_ventas_por_area', fetchData, tablaAreas);


    // fetchDataAndProcess('./gosaccontroller/total_ventas_por_area_gosac', fetchData, data => {
    //     console.log(data);
    // });

    fetchDataAndProcess('./gosaccontroller/obtenerVentas_gosac', fetchData, data => {
        console.log(data);
    });

    fetchDataAndProcess('./gosaccontroller/serviciosYprecioPromedio_gosac', fetchData, data => {
        console.log(data);
    });


    


    fetchDataAndProcess('./ventascontroller/obtenerVentas', fetchData, data => {
        // Procesar las ventas por sede
        renderChartAndTable(data.ventasPorSede);

        renderChartCantidadEspecie(data.ventasPorEspecie);

        // Extraer y procesar los datos de servicios y productos
        const serviciosProductos = data.serviciosProductos[0];
        if (serviciosProductos) {
            document.getElementById('cant_serv_prod').textContent = `Serv: ${parseInt(serviciosProductos.CantidadServicios)}, Prod: ${parseInt(serviciosProductos.CantidadProductos)}`;
            document.getElementById('precio_promedio').textContent = "S/ " + parseFloat(serviciosProductos.precio_promedio).toFixed(2);
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
                    const especies = ["Perro", "Gato"]; // Especies únicas
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
                            plugins: {
                                title: {
                                    display: false
                                    //text: 'Ventas por Sede y Especie'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                },
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: true // Apilado en el eje X
                                },
                                y: {
                                    stacked: true // Apilado en el eje Y
                                }
                            }
                        }
                    });
                }
}

let miGraficoChart;

    const abreviacionesCategorias = {
        "PLAN PROTECCION SALUD GATOS": "PP SALUD GATOS",
        "PLAN PROTECCION SALUD SENIOR GATOS": "PP SALUD SENIOR GATOS",
        "PLAN PROTECCION SALUD SENIOR PERRO": "PP SALUD SENIOR PERRO",
        "PLAN PROTECCION SALUD PERRO": "PP SALUD PERRO",
        "RENOVACION PROTECCION SALUD": "RENOVACION PP SALUD",
        "RENOVACION PROTECCION SALUD SENIOR": "RENOVACION PP SALUD SENIOR"
    };


function renderChartCantidadPlanesSalud(data){
     // Verifica si los datos contienen un error
     if (data.error && data.error === "No se encontraron datos") {
            // Mostrar el mensaje si no se encontraron datos
            document.getElementById('txtmiGrafico').innerHTML = '<p>Aun no hay Ventas de Planes de Salud</p>';
            return; // Detener la ejecución del código si no hay datos
        } else {
            // Limpiar el mensaje si hay datos
            document.getElementById('txtmiGrafico').innerHTML = '';
        }

        // Si se encontraron datos, continuar con la creación del gráfico

        // Obtener las categorías únicas
        const categorias = Object.keys(abreviacionesCategorias);

        // Obtener las sedes únicas
        const sedes = [...new Set(data.map(item => item.sede))];

        // Crear datasets por sede
        const datasets = sedes.map((sede, index) => {
            const colores = ["#FF5733", "#FFC300", "#DAF7A6", "#C70039", "#581845", "#28B463"];
            return {
                label: sede, // Nombre de la sede
                backgroundColor: colores[index % colores.length],
                data: categorias.map(categoria => {
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
                labels: categorias.map(categoria => abreviacionesCategorias[categoria]), // Usar nombres abreviados
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
                                return `${ context.dataset.label }: ${ context.raw } `;
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

        // Obtener el contexto del canvas
        const ctx = document.getElementById('miGrafico').getContext('2d');

        // Destruir el gráfico existente si ya fue creado
        if (miGraficoChart) {
            miGraficoChart.destroy();
        }

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


        // Actualizar los totales en la parte superior
        document.getElementById('ventas-2024').textContent = "S/" + totalVentasSum.toFixed(2);
        document.getElementById('transacciones-2024').textContent = totalVentas;
        document.getElementById('promedio-2024').textContent = "S/" + totalTicketPromedio.toFixed(2);
        document.getElementById('clientes-2024').textContent = totalClientesUnicos;

        document.getElementById('total_ventas').textContent = "S/" + totalVentasSum.toFixed(2);
        document.getElementById('ticket_promedio').textContent = "S/" + totalTicketPromedio.toFixed(2);
        document.getElementById('total_clientes').textContent = totalClientesUnicos;
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
            tbody.appendChild(row);
        });

        // Agregar pie de tabla (tfoot)
        const tfoot = document.getElementById('tablaTotales');
        tfoot.innerHTML = `
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

function llenarTabla2(data) {
        const tbody = document.getElementById('tablaDatos2');
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
            <td>${item.empresa}</td>
            <td>${ item.AREA || 'OTROS' }</td>
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
            tbody.appendChild(row);
        });

        // Agregar pie de tabla (tfoot)
        const tfoot = document.getElementById('tablaTotales2');
        tfoot.innerHTML = `
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

        row += `<td>${totalSede.toFixed(2)}</td>`; // Mostrar la sumatoria de la sede en la última columna
        row += '</tr>';
        rows += row;
    });

    // Paso 5: Crear la fila de sumatorias en el tfoot
    let footerRow = '<tr><td><strong>Total</strong></td>';
    sumatorias.forEach(sumatoria => {
        footerRow += `<td><strong>${sumatoria.toFixed(2)}</strong></td>`; // Mostrar la sumatoria por servicio
    });
    const totalGeneral = sumatorias.reduce((acc, curr) => acc + curr, 0); // Sumar todas las sumas para obtener el total general
    footerRow += `<td><strong>${totalGeneral.toFixed(2)}</strong></td>`; // Mostrar el total general en la última columna
    footerRow += '</tr>';

    // Paso 6: Construir la tabla final
    tabla.innerHTML = `<table><thead>${headerRow}</thead><tbody>${rows}</tbody><tfoot>${footerRow}</tfoot></table>`;
}

function tablaAreas(data) {
    console.log("esta: ",data);
    const tabla = document.getElementById('tablaAreas'); // Suponiendo que hay un elemento con id 'tablaAreas'
    tabla.innerHTML = ''; // Limpiar tabla antes de agregar nuevos datos

    // Paso 1: Crear un conjunto de todas las áreas únicas
    const areas = data.map(item => item.AREA);

    // Paso 2: Crear las cabeceras de la tabla
    let headerRow = '<tr><th>Descripción</th>';
    areas.forEach(area => {
        headerRow += `<th>${area}</th>`;
    });
    headerRow += '<th>Total</th>'; // Agregar la cabecera para la columna de sumatoria
    headerRow += '</tr>';

    // Paso 3: Calcular la sumatoria total de ventas
    const sumaTotalVentas = data.reduce((acc, item) => acc + parseFloat(item.TotalVentas), 0);

    // Paso 4: Crear la fila de "Petmax" con los totales por área
    let petmaxRow = '<tr><td>Petmax</td>';
    let sumatoriaTotal = 0; // Variable para la sumatoria total de las ventas de "Petmax"

    data.forEach(item => {
        const totalVenta = parseFloat(item.TotalVentas);
        petmaxRow += `<td>${totalVenta}</td>`;
        sumatoriaTotal += totalVenta; // Sumar el total de ventas de Petmax
    });

    petmaxRow += `<td>${sumatoriaTotal.toFixed(2)}</td>`; // Mostrar la sumatoria total en la última columna
    petmaxRow += '</tr>';

    // Paso 5: Crear la fila de "% Sobre Venta"
    let porcentajeRow = '<tr><td>% Sobre Venta</td>';
    data.forEach(item => {
        const porcentaje = (parseFloat(item.TotalVentas) / sumaTotalVentas) * 100;
        porcentajeRow += `<td>${porcentaje.toFixed(2)}%</td>`;
    });

    const porcentajeTotal = (sumatoriaTotal / sumaTotalVentas) * 100; // Porcentaje total
    porcentajeRow += `<td>${porcentajeTotal.toFixed(2)}%</td>`; // Mostrar el porcentaje total en la última columna
    porcentajeRow += '</tr>';

    // Agregar las filas calculadas
    tabla.innerHTML = `<thead>${headerRow}</thead><tbody id="tablaAreasBody">${petmaxRow}${porcentajeRow}</tbody>`;

    // Inputs para Días Transcurridos y Días Laborales
    const diasTranscurridosInput = document.getElementById('diasTranscurridos');
    const diasLaboralesInput = document.getElementById('diasLaborales');
    const tableBody = document.getElementById('tablaAreasBody');

    // Función para calcular y mostrar la fila "PROY. PETMAX"
    function updateProyPetmax() {
        const diasTranscurridos = parseFloat(diasTranscurridosInput.value);
        const diasLaborales = parseFloat(diasLaboralesInput.value);

        // Validar los valores de los inputs
        if (isNaN(diasTranscurridos) || diasTranscurridos <= 0 || isNaN(diasLaborales) || diasLaborales <= 0) {
            alert("Por favor ingresa valores válidos para los días.");
            return;
        }

        // Calcular la proyección para cada área
        let proyecciones = [];
        let totalProyeccion = 0;

        data.forEach(item => {
            const proyeccion = (parseFloat(item.TotalVentas) / diasTranscurridos) * diasLaborales;
            proyecciones.push({ area: item.AREA, proyeccion });
            totalProyeccion += proyeccion;
        });

        // Eliminar la fila anterior de "PROY. PETMAX"
        const existingProyRow = document.getElementById('proyPetmaxRow');
        if (existingProyRow) {
            existingProyRow.remove();
        }

        // Crear la nueva fila "PROY. PETMAX"
        const trProy = document.createElement('tr');
        trProy.id = 'proyPetmaxRow';
        trProy.innerHTML = `<td>PROY. PETMAX</td>` +
            proyecciones.map(p => `<td>${p.proyeccion.toFixed(2)}</td>`).join('') +
            `<td>${totalProyeccion.toFixed(2)}</td>`;

        // Agregar la fila al cuerpo de la tabla
        tableBody.appendChild(trProy);
    }

    // Llamar a la función desde el inicio para mostrar la fila "PROY. PETMAX"
    updateProyPetmax();

    // Escuchar los eventos de cambio en los inputs
    diasTranscurridosInput.addEventListener('input', updateProyPetmax);
    diasLaboralesInput.addEventListener('input', updateProyPetmax);
}




    });
</script>