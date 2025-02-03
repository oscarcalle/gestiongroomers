<main class="page-content">
    <div class="container-fluid">
        
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
    <li class="breadcrumb-item"><i class="fa fa-tachometer-alt"></i> Control Panel</li>
    <li class="breadcrumb-item active" aria-current="page">Comparativo</li>
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



          <!-- Select para seleccionar el año -->
          <div class="form-group">
            <label for="anioSeleccionado">Seleccione Año:</label>
            <select class="form-control" id="anioSeleccionado" ng-model="anioSeleccionado" ng-options="anio for anio in anios" ng-change="actualizarMeses()">
                <option value="" disabled>Seleccione un año</option>
            </select>
        </div>

        <label>Seleccione hasta 2 meses:</label>
                <div class="form-group">
                    <div class="row">
                        <!-- Recorrer meses dinámicamente con AngularJS -->
                        <div class="col-4" ng-repeat="mes in meses track by $index">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" ng-model="mes.seleccionado" ng-change="verificarSeleccionMeses(mes)" id="mes-{{mes.id}}" >
                                <label class="form-check-label"  for="mes-{{mes.id}}">
                                    {{mes.nombre}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
        
          
        <div class="card-body">
            <canvas id="ventasChart"></canvas>
        </div>
        <div class="card-body">
            <canvas id="crecimientoChart"></canvas>
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


    });
</script>