<main class="page-content">
    <div class="container-fluid">
        
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
    <li class="breadcrumb-item"><i class="fa fa-cogs"></i> Configuraciones</li>
    <li class="breadcrumb-item active" aria-current="page">Subir Data Gosac </li>
  </ol>
</nav>

<h2>Subir Data Gosac</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="file" class="form-label">Seleccionar archivo XLSX:</label>
            <input type="file" class="form-control" id="file" name="file" accept=".xlsx" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>
            Subir</button>
    </form>
    <div id="response" class="mt-3"></div>


         <!-- Tabla de registros -->
         <div id="dataTableContainer" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        
        <h3 class="mb-0">Registros Subidos</h3>

    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cantidad de Registros</th>
                <th>Fecha de Subida</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="dataTableBody"></tbody>
    </table>
</div>

    </div>
</main>

<script>
    $(document).ready(function () {

        function toggleLoader(show) {
            $('#loader').css('visibility', show ? 'visible' : 'hidden');
        }

        function loadDataTable() {
            $.ajax({
                url: './gosac/get_data_summary',
                type: 'GET',
                success: function (response) {
                    console.log(response);
                    try {
                        let res = JSON.parse(response);
                        if (res.status === 'success' && Array.isArray(res.data) && res.data.length > 0) {
                            let tableBody = '';
                            res.data.forEach(rowData => {
                                if (rowData.cantidad_registros > 0) {
                                    // rowData.fecha_subida tiene formato 'YYYY-MM-DD HH:MM:SS' convertir a YYYY-MM-DD
                                    let fechaSubida = rowData.fecha_subida.split(' ')[0];
                                    tableBody += `
                                         <tr> 
                                            <td>${rowData.cantidad_registros}</td>
                                            <td>${fechaSubida}</td>
                                            <td>
                                                <a href="./gosac/download_excel/${fechaSubida}" class="btn btn-success btn-sm">
                                                    <i class="fas fa-download"></i> Descargar
                                                </a>
                                                <button class="btn btn-danger btn-sm delete-btn" data-fecha="${fechaSubida}">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    `;
                                }
                            });
                            $('#dataTableBody').html(tableBody);
                            $('#dataTableContainer').show();
                        } else {
                            $('#dataTableContainer').hide();
                            console.warn('No se recibieron datos o el formato es incorrecto.');
                        }
                    } catch (error) {
                        console.error('Error al procesar la respuesta:', error);
                    }
                },
                error: function () {
                    console.error('Error al cargar los datos.');
                }
            });
        }

    function deleteRecord(fecha) {
    $.ajax({
        url: './gosac/delete_record',
        type: 'POST',
        data: { fecha_subida: fecha },
        success: function (response) {
            let res = JSON.parse(response);
            if (res.status === 'success') {
                alert('Registro eliminado con éxito.');
                loadDataTable(); // Recargar la tabla después de eliminar
            } else {
                alert('Error al eliminar el registro: ' + res.message);
            }
        },
        error: function () {
            alert('Error al procesar la solicitud de eliminación.');
        }
    });
}

loadDataTable();

        $('#uploadForm').on('submit', function (e) {
            e.preventDefault();

            let fileInput = $('#file')[0];
            let file = fileInput.files[0];

            // Validar tipo de archivo en el frontend
            if (file && file.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                $('#response').html('<div class="alert alert-danger">Por favor, selecciona un archivo .xlsx válido.</div>');
                return;
            }

            let formData = new FormData(this);

            // Mostrar el loader
            toggleLoader(true);

            $.ajax({
                url: './gosac/upload_process', // Ruta al controlador en CodeIgniter
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('#response').html('<div class="alert alert-success">' + res.message + '</div>');
                    } else {
                        $('#response').html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                },
                error: function () {
                    $('#response').html('<div class="alert alert-danger">Error al subir el archivo.</div>');
                },
                complete: function() {
                    // Ocultar el loader después de que se complete el proceso
                    toggleLoader(false);
                }
            });
        });
    });
</script>