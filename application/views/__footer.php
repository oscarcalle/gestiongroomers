<!-- <div style="position: fixed; bottom: 0; width: 100%; box-shadow: 0px -1px 5px #282c33; background: #2832D4; font-size: 12px;height: 31px; color: #FFF; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
<?php
// Establecer la zona horaria a Lima
date_default_timezone_set('America/Lima');
setlocale(LC_TIME, 'es_PE.UTF-8'); // Configurar idioma a español
?>

    <div>
        &copy; Petmax SAC
    </div>
    <div id="fecha-hora">
        <?php echo strftime("%A, %d de %B de %Y %H:%M:%S"); ?> 
    </div>

</div> -->



<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a class="btn btn-primary" href="<?php echo base_url('login/logout'); ?>">Cerrar sesión</a>
      </div>
    </div>
  </div>
</div>

 <script>
     document.getElementById('openModal').addEventListener('click', function (event) {
    event.preventDefault();
    var myModal = new bootstrap.Modal(document.getElementById('logoutModal'));
    myModal.show();
  });
  
// Función para actualizar la fecha y hora cada segundo
/*function actualizarFechaHora() {
    const fechaHoraElement = document.getElementById('fecha-hora');
    const fechaHora = new Date().toLocaleString('es-PE', {
        weekday: 'long', // Día de la semana completo
        year: 'numeric', 
        month: 'long', // Mes completo
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit', // Incluir los segundos
        hour12: true // Formato de 12 horas (AM/PM)
    });
    fechaHoraElement.innerHTML = fechaHora;
}

// Actualizar la fecha y hora al cargar la página
actualizarFechaHora();

// Actualizar la fecha y hora cada segundo (1000 milisegundos)
setInterval(actualizarFechaHora, 1000);*/
</script> 
  
</body>
</html>
