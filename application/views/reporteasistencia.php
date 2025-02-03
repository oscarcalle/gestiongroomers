<main class="page-content">
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-clock"></i> Asistencia</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reporte</li>
            </ol>
        </nav>

        <!-- Filtro de fechas -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="rango_fechas" id="rangoFechas" class="form-control" placeholder="Selecciona un rango de fechas" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                    <button id="reporteVentasDetalladasButton" class="btn btn-success"><i class="fa fa-file-excel"></i> Reporte</button>

                </div>
            </div>

        <!-- Tabla de reporte -->
        <table class="table table-bordered table-sm" style="background: white; border: 1px solid #ddd;">
            <thead id="table-head">
                <!-- Aquí se cargarán las fechas dinámicamente -->
            </thead>
            <tbody id="table-body">
                <!-- Aquí se cargarán los datos de asistencia dinámicamente -->
            </tbody>
        </table>
    </div>
</main>

<script>
    $(document).ready(function() {

        // Inicializar daterangepicker con configuración y rangos predefinidos
        $('#rangoFechas').daterangepicker({
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
            },
            locale: {
                format: 'YYYY-MM-DD',
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
            maxSpan: {
                days: 31
            },
            startDate: moment().startOf('month'), // Establecer el inicio del mes actual
            endDate: moment().endOf('month')      // Establecer el final del mes actual
        }, function(start, end) {
            // Llamada a la función para actualizar la tabla cuando el rango cambia
            cargarTabla(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        // Llamada inicial para cargar el reporte con el mes actual por defecto
        cargarTabla(moment().startOf('month').format('YYYY-MM-DD'), moment().endOf('month').format('YYYY-MM-DD'));
    });

    function toggleLoader(show) {
        $('#loader').css('visibility', show ? 'visible' : 'hidden');
    }

    function getFetchData() {
        // Obtener las fechas seleccionadas del daterangepicker
        var rangoFechas = $('#rangoFechas').data('daterangepicker');
        var fecha_inicio = rangoFechas.startDate.format('YYYY-MM-DD');
        var fecha_fin = rangoFechas.endDate.format('YYYY-MM-DD');

        // Asegurarse de que las fechas sean válidas
        if (!fecha_inicio || !fecha_fin) {
            toastr.error('Por favor seleccione un rango de fechas');
            return null;
        }

        return {
            start: fecha_inicio,
            end: fecha_fin
        };
    }

    $('#reporteVentasDetalladasButton').on('click', function () {
        descargarExcelAsistenciaDetalladas();
    });

    function descargarExcelAsistenciaDetalladas() {
        const fetchData = getFetchData();
        if (!fetchData) return; // Si no se proporcionan fechas válidas, no continuar

        descargarExcel('./reporteasistencia/descargarExcelAsistenciaDetalladas', 'reporte_asistencias_detalladas.xlsx');
    }

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

    // Función para cargar la tabla con los datos
    function cargarTabla(fecha_inicio, fecha_fin) {
        toggleLoader(true);
        
        $.ajax({
            url: '<?= base_url("/reporteasistencia/cargar_reporte_ajax"); ?>',
            data: {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin
            },
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    var reporte = response.reporte;
                    var fechas_rango = response.fechas_rango;

                    // Cargar el encabezado de la tabla con las fechas
                    var tableHead = '<tr><th><i class="fa fa-user"></i> Nombre</th>';
                    fechas_rango.forEach(function(fecha) {
                        // Crear un objeto de fecha con la zona horaria adecuada
                        var dia = new Date(fecha + 'T00:00:00'); // 'T00:00:00' asegura que se interprete como medianoche en UTC
                        var dia_nombre = dia.toLocaleString('es', { weekday: 'short' });
                        var dia_numero = dia.getDate();
                        var color_dia = dia_nombre === 'dom' ? 'style="color:red;"' : '';
                        tableHead += `<th class="text-center" ${color_dia}>${dia_nombre}<br>${dia_numero}</th>`;
                    });
                    tableHead += '</tr>';
                    $('#table-head').html(tableHead);

                    // Cargar el cuerpo de la tabla con los datos de asistencia
                    var tableBody = '';
                    for (var empleado in reporte) {
                        var row = `<tr><td>${empleado}</td>`;
                        fechas_rango.forEach(function(fecha) {
                            var estado = reporte[empleado][fecha] || '';
                            var color = '';
                            if (estado === 'presente') {
                                color = 'background-color: green;';
                            } else if (estado === 'tarde') {
                                color = 'background-color: orange;';
                            } else if (estado === 'ausente') {
                                color = 'background-color: red;';
                            }
                            row += `<td style="${color}"></td>`;
                        });
                        row += '</tr>';
                        tableBody += row;
                    }
                    $('#table-body').html(tableBody);
                }
                toggleLoader(false);
            },
            error: function() {
                alert('Error al cargar los datos');
            }
        });
    }
</script>
