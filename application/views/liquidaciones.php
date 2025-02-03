<main class="page-content">
  <div class="container-fluid">

  <nav aria-label="breadcrumb" id="breadcrumb" class="main-breadcrumb mb-1">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
        <li class="breadcrumb-item"><i class="fa fa-globe"></i> Indicadores</li>
        <li class="breadcrumb-item active" aria-current="page">Liquidaciones</li>
      </ol>
    </nav>

  <div  id="contenido">
        <div class="m-4">
            <img src="https://gestionveterinariagroomers.com/beta/assets/images/logo.png" width="250" alt="">
            <h3 class="mt-4">
                Liquidación Venta Plan Salud
            </h3>
        </div>
        <div class="row filtros align-items-center">
            <div class="col-md-4 form-group">
                <label for="daterange">Periodo:</label>
                <input type="text" id="daterange" class="form-control">
            </div>
            <div class="col-md-4 form-group">
                <label for="usuario">Usuario:</label>
                <select id="usuario" class="form-control"></select>
            </div>
            <div class="col-md-4 form-group" id="comisionfiltro">
                <label for="comision">Comisión S/. </label>
                <input type="number" id="comision" class="form-control" value="10" min="1" step="0.01">
            </div>
        </div>
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre Cliente</th>
                        <th>Mascota</th>
                        <th>Fecha Venta</th>
                        <th>Boleta</th>
                        <th>Sede</th>
                        <th>Importe</th>
                        <th>Pagado</th>
                    </tr>
                </thead>
                <tbody id="tabla-resultados">
                    <!-- Aquí se mostrarán los resultados -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><b>Total de Ventas:</b></td>
                        <td colspan="2" id="totalVentas">0</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Total a Liquidar:</b></td>
                        <td colspan="2" id="totalPagadas">0</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Total del Importe:</b></td>
                        <td colspan="2" id="totalImporte">0</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-3">
            <div class="mt-3">
                <b>Fecha de liquidación:</b> <span id="fecha-liquidacion"></span>
            </div>
            <p class="mt-3">
                Recibí de Petmax SAC la cantidad de _________________________________ por concepto de venta Plan Salud.
            </p>
            <p class="mt-3">
                Nombre y Apellidos:
                ______________________________________________________________________________________.
            </p>
            <p class="mt-3">
                DNI: _________________________________.
            </p>
            <p class="mt-3">
                Sede: ________________________________.
            </p>
        </div>

        <input type="hidden" id="registrandoEmail" name="registrandoEmail" value="soporte.groomers@gmail.com">
        <!-- Botones para acciones -->
        <div class="mt-5" id="botones">
            <!-- <button id="filtrar" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar</button> -->
            <button id="imprimir" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir</button>
        </div>
    </div>

  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    $('#menu-btn').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#contenido').toggleClass('active');
            });

            //Swal.fire('Error', 'Hubo un error al cargar los datos: ', 'error');
            //Swal.fire('¡Éxito!', 'Los datos se cargaron correctamente.', 'success');
            //Swal.fire('Información', 'Este es un mensaje informativo.', 'info');
            //Swal.fire('Advertencia', 'Este es un mensaje de advertencia.', 'warning');

            const fechaLima = moment().tz("America/Lima").format("YYYY-MM-DD hh:mm:ss");
            $('#fecha-liquidacion').text(fechaLima);

            $('#daterange').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            function cargarTotales() {
                let totalVentas = 0;
                let totalPagadas = 0;
                let totalImporte = 0;

                $('#tabla-resultados tr').each(function () {
                    const checkbox = $(this).find('.pagado-checkbox');
                    const isDisabled = checkbox.is(':disabled'); // Verificar si el checkbox está deshabilitado

                    // Solo contar los que están habilitados
                    if (checkbox.is(':checked') && !isDisabled) {
                        // Obtener el texto del importe y convertirlo en número
                        const importeTexto = $(this).find('td:nth-child(6)').text().trim();
                        const importe = parseFloat(importeTexto.replace(',', '').replace('$', ''));

                        if (!isNaN(importe)) {
                            totalImporte += importe;
                            totalPagadas++;
                        }
                    }

                    // Sumar todas las ventas, independientemente de si están pagadas o no
                    if (!isDisabled) {
                        totalVentas++;
                    }
                });

                $('#totalVentas').text(totalVentas);
                $('#totalPagadas').text(totalPagadas);
                $('#totalImporte').text(totalImporte.toFixed(2));
            }

            function ejecutarFiltro() {
    $('#loader').css('visibility', 'visible');
    const [fechaInicio, fechaFin] = $('#daterange').val().split(' - ');
    const usuario = $('#usuario').val();
    const comision = parseFloat($('#comision').val()) || 10; // Convertir comisión a número

    fetch(`./liquidaciones/getSalesData?action=liquidaciones&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}&usuario=${usuario}`)
        .then(response => response.json())
        .then(data => {
            $('#loader').css('visibility', 'hidden');
            let rows = '';
            data.forEach(item => {
                const cliente = item.Cliente || item.RazonSocial;
                const estaPagado = item.ImportePagado !== null; // Verificar si hay un importe pagado

                rows += `
                    <tr data-saleid="${item.SaleId}">
                        <td>${cliente}</td>
                        <td>${item.Mascota}</td>
                        <td>${item.FechaDelDocumento}</td>
                        <td>${item.NoSerie}-${item.NoCorrelativo}</td>
                        <td>${item.NombreSede}</td>
                        <td>${estaPagado ? item.ImportePagado : comision.toFixed(2)}</td>
                        <td><input type="checkbox" class="pagado-checkbox" ${estaPagado ? 'checked disabled' : ''}></td>
                    </tr>
                `;
            });

            $('#tabla-resultados').html(rows);
            cargarTotales();

            $('.pagado-checkbox').change(function () {
                cargarTotales();
            });
        })
        .catch(error => {
            $('#loader').css('visibility', 'hidden');
            console.error('Error:', error);
        });
}

            // Actualizar la tabla cuando cambie el valor de comisión, usuario o rango de fechas
            $('#comision').on('input', ejecutarFiltro);
            $('#usuario').change(ejecutarFiltro);
            $('#daterange').on('apply.daterangepicker', ejecutarFiltro);

            $('#filtrar').on('click', ejecutarFiltro);

            // Cargar opciones de usuario
            fetch(`./liquidaciones/getUsuarios`)
                .then(response => response.json())
                .then(usuarios => {
                    usuarios.forEach(usuario => {
                        $('#usuario').append(new Option(usuario, usuario));
                    });
                })
                .catch(error => console.error('Error al cargar usuarios:', error));
        });

        document.getElementById('imprimir').addEventListener('click', function () {
    Swal.fire({
        title: '¿Desea realizar la liquidación?',
        text: 'Este proceso no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, liquidar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            const liquidaciones = [];
            $('#tabla-resultados tr').each(function () {
                const checkbox = $(this).find('.pagado-checkbox');
                const isDisabled = checkbox.is(':disabled');

                if (checkbox.is(':checked') && !isDisabled) {
                    const saleId = $(this).data('saleid');
                    const boleta = $(this).find('td:nth-child(4)').text().trim().replace(',', '').replace('$', '');
                    const importe = parseFloat($(this).find('td:nth-child(6)').text().trim().replace(',', '').replace('$', ''));
                    const usuario = $('#usuario').val();
                    const usuarioRegistrando = $('#registrandoEmail').val();
                    const fechaLiquidacion = $('#fecha-liquidacion').text();

                    liquidaciones.push({
                        SaleId: saleId,
                        boleta: boleta,
                        importe: importe,
                        usuario: usuario,
                        usuarioRegistrando: usuarioRegistrando,
                        fechaLiquidacion: fechaLiquidacion
                    });
                } else {
                    $(this).addClass('ocultar'); // Ocultar filas no seleccionadas
                }
            });

            if (liquidaciones.length > 0) {
                fetch('./liquidaciones/saveLiquidaciones', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(liquidaciones)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.boletasPagadas && data.boletasPagadas.length > 0) {
                            Swal.fire({
                                title: 'Boletas Pagadas',
                                html: 'Las siguientes boletas ya fueron pagadas:<br>' + data.boletasPagadas.join('<br>') + '.<br>No se realizaron cambios. Por favor, deseleccione las boletas pagadas.',
                                icon: 'info',
                            });
                            $('#tabla-resultados tr').removeClass('ocultar'); // Mostrar las filas de nuevo
                        } else if (data.success) {
                            setTimeout(() => {
                                window.print();
                            }, 100); // Retraso para garantizar que todo esté listo para imprimir
                            
                            // Después de imprimir, restaurar visibilidad de las filas
                            window.addEventListener('afterprint', function () {
                                $('#tabla-resultados tr').removeClass('ocultar');
                                ejecutarFiltro();
                            });
                        } else {
                            Swal.fire('Error', 'Ocurrió un error al guardar las liquidaciones', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                Swal.fire('Advertencia', 'No hay planes seleccionados para liquidar', 'warning');
            }
        }
    });
});

        // Para ejecutar el filtro después de la impresión
        window.addEventListener('beforeprint', function () {
            // Limpiar el evento anterior antes de establecer uno nuevo
            window.removeEventListener('afterprint', ejecutarFiltro);
        });

        window.addEventListener('afterprint', function () {
            ejecutarFiltro();
        });
</script>