<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<main class="page-content">
  <div class="container-fluid">

    <nav aria-label="breadcrumb" class="main-breadcrumb mb-1">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
        <li class="breadcrumb-item"><i class="fa fa-cogs"></i> Configuraciones</li>
        <li class="breadcrumb-item active" aria-current="page">Metas del mes</li>
      </ol>
    </nav>

    <h2>Metas del Mes</h2>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate"><i class="fa fa-plus"></i> Nuevo</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Sede</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Meta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($metas as $meta): ?>
                <tr>
                    <!-- <td><?= $meta['idmeta']; ?></td> -->
                    <td><?= $meta['sede_nombre']; ?></td>
                    <td><?= $meta['mes']; ?></td>
                    <td><?= $meta['anio']; ?></td>
                    <td><?= $meta['meta']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editMeta(<?= $meta['idmeta']; ?>)"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $meta['idmeta']; ?>)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

  </div>
</main>

<!-- Modal de Crear/Editar -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateLabel">Agregar Meta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMeta" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="idsede" class="form-label">Sede</label>
                        <select id="idsede" name="idsede" class="form-select" required>
                            <?php foreach ($sedes as $sede): ?>
                                <option value="<?= $sede['TenantId']; ?>"><?= $sede['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mes" class="form-label">Mes</label>
                        <input type="text" name="mes" id="mes" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="anio" class="form-label">Año</label>
                        <input type="number" name="anio" id="anio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="meta" class="form-label">Meta</label>
                        <input type="number" step="0.01" name="meta" id="meta" class="form-control" required>
                    </div>
                    <input type="hidden" name="idmeta" id="idmeta">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function editMeta(id) {
        $.ajax({
            url: '<?= site_url('metas/edit') ?>/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#idmeta').val(data.idmeta);
                $('#idsede').val(data.idsede);
                $('#mes').val(data.mes);
                $('#anio').val(data.anio);
                $('#meta').val(data.meta);
                $('#modalCreateLabel').text('Editar Meta');
                $('#modalCreate').modal('show');
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('metas/delete') ?>/' + id;
            }
        });
    }

    $('#formMeta').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: '<?= site_url('metas/store') ?>',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#modalCreate').modal('hide');
                location.reload();
            }
        });
    });
</script>