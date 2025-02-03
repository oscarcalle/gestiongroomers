<main class="page-content">
  <div class="container-fluid">
    <h2>Configuración de Menús</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-target="#menuModal">Agregar Menú</button>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Icono</th>
          <th>Ruta</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($todos_menus as $item): ?>
        <tr>
          <td><?= $item['idmenu']; ?></td>
          <td><?= $item['nombre']; ?></td>
          <td><i class="fa <?= $item['icono']; ?>"></i></td>
          <td><?= $item['ruta']; ?></td>
          <td><?= $item['estado']; ?></td>
          <td>
            <button class="btn btn-warning btn-sm" onclick="editMenu(<?= $item['idmenu']; ?>)"><i class="fa fa-edit"></i></button>
            <button class="btn btn-danger btn-sm" onclick="deleteMenu(<?= $item['idmenu']; ?>)"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div> <!-- /.container-fluid -->
</main>

<!-- Modal para agregar/editar menú -->
<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="menuForm">
        <div class="modal-header">
          <h5 class="modal-title" id="menuModalLabel">Agregar Menú</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idmenu" name="idmenu">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="icono">Icono</label>
            <input type="text" class="form-control" id="icono" name="icono">
          </div>
          <div class="form-group">
            <label for="ruta">Ruta</label>
            <input type="text" class="form-control" id="ruta" name="ruta">
          </div>
          <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado">
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function editMenu(id) {
      // Implementar la lógica para editar menú
      $.ajax({
          url: '<?= base_url("Configurarmenu/get_menu/") ?>' + id,
          method: 'GET',
          success: function(data) {
              const menu = JSON.parse(data);
              $('#idmenu').val(menu.idmenu);
              $('#nombre').val(menu.nombre);
              $('#icono').val(menu.icono);
              $('#ruta').val(menu.ruta);
              $('#estado').val(menu.estado);
              $('#menuModalLabel').text('Editar Menú');
              $('#menuModal').modal('show');
          }
      });
  }

  $('#menuForm').on('submit', function(e) {
      e.preventDefault();
      // Implementar lógica para guardar menú
      const formData = $(this).serialize();
      $.ajax({
          url: '<?= base_url("Configurarmenu/save_menu") ?>',
          method: 'POST',
          data: formData,
          success: function() {
              Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'Menú guardado correctamente.',
              }).then(() => {
                  location.reload(); // Recargar la página para ver los cambios
              });
          },
          error: function() {
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Ocurrió un problema al guardar el menú.',
              });
          }
      });
  });

  function deleteMenu(id) {
      Swal.fire({
          title: '¿Estás seguro?',
          text: "¡No podrás recuperar este menú después de eliminarlo!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: '<?= base_url("Configurarmenu/delete_menu/") ?>' + id,
                  method: 'POST',
                  success: function() {
                      Swal.fire({
                          icon: 'success',
                          title: 'Eliminado',
                          text: 'El menú ha sido eliminado.',
                      }).then(() => {
                          location.reload(); // Recargar la página después de eliminar
                      });
                  },
                  error: function() {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'Ocurrió un problema al eliminar el menú.',
                      });
                  }
              });
          }
      });
  }
</script>
