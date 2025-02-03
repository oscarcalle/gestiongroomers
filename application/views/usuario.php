<main class="page-content">
    <div class="container-fluid">

    <h2>Lista de Usuarios</h2>
    <button class="btn btn-primary mb-3" id="addUserBtn">Agregar Usuario</button>
    <div id="message"></div>
    <table class="table table-bordered" id="usuariosTable">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Nombre</th>
                <th>Email</th>
                <th>Nivel</th>
                <th>Sedes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr id="usuario-<?= $usuario['idusuario']; ?>">
                    <!-- <td><?= $usuario['idusuario']; ?></td> -->
                    <td><?= $usuario['nombre']; ?></td>
                    <td><?= $usuario['email']; ?></td>
                    <td><?= $usuario['nivel_nombre']; ?></td> <!-- Cambia aquí -->
                    <td><?= $usuario['sedes']; ?></td>
                    <td>
                        <button class="btn btn-warning edit-btn" data-id="<?= $usuario['idusuario']; ?>">Editar</button>
                        <button class="btn btn-danger" onclick="confirmDelete(<?= $usuario['idusuario']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    </main>

    <!-- Modal para agregar/editar usuario -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Agregar Usuario</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <input type="hidden" id="idusuario" name="idusuario">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="idnivel">Nivel</label>
                            <select class="form-control" id="idnivel" name="idnivel" required>
                                <option value="">Seleccione un nivel</option>
                                <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?= $nivel['idnivel']; ?>"><?= $nivel['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sedes">Sedes</label>
                            <input type="text" class="form-control" id="sedes" name="sedes"  placeholder="Ingrese sedes">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        // Agregar usuario
        $('#addUserBtn').click(function() {
            $('#userModalLabel').text('Agregar Usuario');
            $('#userForm')[0].reset();
            $('#userModal').modal('show');
        });

        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#idusuario').val();
            let url = id ? '<?= site_url('usuario/update/'); ?>' + id : '<?= site_url('usuario/store'); ?>';
            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#userModal').modal('hide');
                        Swal.fire('Éxito!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    }
                }
            });
        });

        // Editar usuario
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '<?= site_url('usuario/get_by_id/'); ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(usuario) {
                    if (usuario) {
                        $('#userModalLabel').text('Editar Usuario');
                        $('#idusuario').val(usuario.idusuario);
                        $('#nombre').val(usuario.nombre);
                        $('#email').val(usuario.email);
                        $('#idnivel').val(usuario.idnivel).change(); // Establecer el nivel
                        $('#sedes').val(usuario.sedes);
                        $('#userModal').modal('show');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo obtener el usuario', 'error');
                }
            });
        });

    });

    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminarlo!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url('usuario/delete/'); ?>' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#usuario-' + id).remove();
                            Swal.fire('Eliminado!', response.message, 'success');
                        }
                    }
                });
            }
        });
    }
</script>