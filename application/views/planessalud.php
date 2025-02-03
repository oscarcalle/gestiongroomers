<main class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
            <li class="breadcrumb-item"><i class="fa fa-chart-line"></i> Informes</li>
            <li class="breadcrumb-item active" aria-current="page">Planes Salud</li>
            </ol>
        </nav>

        <h3 class="mt-2"><i class="fa fa-medkit"></i> Planes Salud</h3>

        <div class="row mt-3">
            <div class="col-md-6">
            <div class="form-group">
                <label for="sedesSelect">
                <i class="fa fa-calendar"></i> Rango de Fechas:
                </label>
                <input type="text" class="form-control" name="daterange" id="daterange" title="Elige una fecha para filtrar" data-toggle="tooltip" tooltip style="width: 100%;height: 41px;" />
            </div>
            </div>
            <div class="col-md-6">
            <!-- Selector de Sedes -->
            <div class="form-group">
                <label for="sedesSelect">
                <i class="fa fa-building"></i> Sedes:
                </label>
                <select id="sedesSelect" class="form-control selectpicker" multiple 
                title="Selecciona Sedes" data-live-search="true">
                <?php foreach ($sedes as $sede): ?>
                    <option value="<?= htmlspecialchars($sede['TenantId']) ?>" selected>
                    <?= htmlspecialchars($sede['nombre']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 mb-3">
            <!-- Lista de Opciones -->
            <ul class="list-group" id="tipoConsultaList">
                <?php
                $opciones = [
                "" => "Todos",
                 "vigente" => "Vigentes",
                 "vencido" => "Vencidos"
                // "nombre_empieza_con" => 'Nombre "Fam"',
                // "sin_nombre" => "Clientes con Identificación pero sin Nombre",
                // "sin_identificacion" => "Clientes con Nombre pero sin Identificación",
                // "numeros_en_nombre" => "Números en Nombre",
                // "numeros_en_apellidos" => "Números en Apellidos",
                // "contiene_no_atender" => "Contiene palabras (Atender, Usar)",
                // "nombre_corto" => "Nombres Cortos (<4 caracteres)",
                // "nombre_largo" => "Nombres Largos (>20 caracteres)"
                ];

                foreach ($opciones as $key => $texto): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center 
                    <?= $key === "" ? 'active' : '' ?>" 
                    data-tipo-consulta="<?= htmlspecialchars($key) ?>">
                    <?= htmlspecialchars($texto) ?>
                </li>
                <?php endforeach; ?>
            </ul>
            </div>
            <div class="col-md-10">
            <!-- Tabla -->
                <table id="tablaDatos" class="table">
                <thead></thead>
                <tfoot></tfoot>
                </table>
            </div>
        </div>
        </div>
    </main>

    <script>
        // Inicializar el rango de fechas
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
            'Año anterior': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            'Todo el Historial': [moment('2014-01-01'), moment()]
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
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
        });

        // Escuchar cambios en el rango de fechas y reiniciar la tabla
        $('#daterange').on('apply.daterangepicker', function () {
        initDatatable();
        });

        // Inicializar tipoConsulta con "Todos" por defecto
        let tipoConsulta = '';

        // Actualizar lista activa
        const updateActiveItem = (item) => {
        document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active'));
        item.classList.add('active');
        tipoConsulta = item.getAttribute('data-tipo-consulta');
        initDatatable();
        };

        // Inicializar eventos en la lista
        document.querySelectorAll('#tipoConsultaList .list-group-item').forEach(item => {
        item.addEventListener('click', () => updateActiveItem(item));
        });

        $('#tablaDatos')
        .on('processing.dt', function (e, settings, processing) {
        // Mostrar u ocultar el loader basado en el estado de procesamiento
        $('#loader').css('visibility', processing ? 'visible' : 'hidden');
        })
        .on('draw.dt', function () {
        // Asegurarse de ocultar el loader después de que la tabla se renderiza
        $('#loader').css('visibility', 'hidden');
        });

        // Inicializar el DataTable
        const initDatatable = () => {
            const columns = [
            { data: "fecha_actualizacion", title: "Actualización" },
            { data: "plan_salud_id", title: "ID Plan Salud" },
            { data: "fecha_inicio", title: "Fecha Inicio" },
            { data: "fecha_fin", title: "Fecha Fin" },
            { data: "no_contrato", title: "Contrato" },
            { data: "mascota", title: "Mascota" },
            {
                data: "estado",
                title: "Estado",
                render: function (data, type, row) {
                    if (type === "display") {
                        const badgeClass = data === "Vigente" ? "bg-success" : "bg-danger";
                        return `<span class="badge ${badgeClass} fs-6">${data}</span>`;
                    }
                    return data; // Para exportación y otros usos
                }
            },
            { data: "sede", title: "Sede" },
            {
                "data": "plan_salud_id",
                "title": "Opciones",
                "render": function (data, type, row) {
                    return '<a href="./planessalud/' + data + '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
                },
                "orderable": false
            }
        ];

        // Destruir DataTable si ya existe
        if ($.fn.DataTable.isDataTable('#tablaDatos')) {
            $('#tablaDatos').DataTable().destroy();
        }

        // Crear el <thead> dinámicamente con los títulos de las columnas
        const theadHtml = `<tr>${columns.map(col => `<th>${col.title}</th>`).join('')}</tr>`;
        $('#tablaDatos thead').html(theadHtml);

        // Crear el <tfoot> dinámicamente con inputs para filtrado, omitiendo la columna "Opciones"
        const tfootHtml = `<tr>${columns.map((col, index) => {
            if (col.title !== "Opciones") { // Si la columna no es "Opciones", agrega el input de filtrado
                return `<th><input type="text" class="form-control" placeholder="Filtrar" /></th>`;
            }
            return "<th></th>"; // No agregar un input en la columna "Opciones"
        }).join('')}</tr>`;
        $('#tablaDatos tfoot').html(tfootHtml);

        // Verificar el tamaño de la pantalla y configurar scrollX
        const isSmallScreen = $(window).width() < 1921; // Establecer el umbral según tus necesidades

        console.log("Resolucion Width: ", $(window).width());

        // Inicializar el DataTable
        const table = $('#tablaDatos').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            responsive: true,
            ajax: {
                url: "./planessalud/getData",
                type: "POST",
                data: (d) => {
                    const dateRange = $('#daterange').val()?.split(' - ') || ['', ''];
                    d.fecha_inicio = dateRange[0];
                    d.fecha_fin = dateRange[1];
                    d.sede = $('#sedesSelect').val() || [];
                    d.tipo_consulta = tipoConsulta;
                    $('#tablaDatos tfoot input').each(function (i) {
                        d.columns[i].search.value = $(this).val(); // Asigna el valor del filtro de cada columna
                    });
                },
                error: () => {
                    $("#tablaDatos").html('<tbody><tr><td colspan="' + columns.length + '">No se encontraron datos en el servidor</td></tr></tbody>');
                }
            },
            language: { url: "./assets/js/Spanish.json" },
            columns: columns,
            order: [[0, 'desc']],
            scrollX: isSmallScreen ? '100%' : false,  // Aplica scrollX solo si es pantalla pequeña
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100, 200, 500],
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', text: 'Excel', "action": newexportaction },
                { extend: 'copy', text: 'Copiar', "action": newexportaction }
            ]
        });

        // Agregar el input de filtrado en cada celda del tfoot, excepto en la columna "Opciones"
        $('#tablaDatos tfoot th').each(function (i) {
            const title = $('#tablaDatos thead th').eq(i).text();  // Obtener el título de la columna

            // Solo agregar el input si el título no es "Opciones"
            $(this).html(title !== "Opciones" 
                ? `<input type="text" class="form-control" placeholder="Filtrar ${title}" data-index="${i}" />` 
                : ''
            );
        });

        // Filtrar por columna cuando se teclea en los inputs del tfoot
        $('#tablaDatos tfoot input').on('keyup', function () {
            table.ajax.reload(); // Recarga el DataTable con los nuevos filtros
        });
    };


    function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }

    // Inicializar la tabla
    initDatatable();
</script>