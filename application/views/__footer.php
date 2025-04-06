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

   // Elementos necesarios
   const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const mainNavbar = document.getElementById('mainNavbar');

  // Obtener tema guardado o usar claro por defecto
  let isDarkTheme = localStorage.getItem('theme') === 'dark';

  // Función para aplicar el tema
  function applyTheme() {
    document.documentElement.setAttribute("data-bs-theme", isDarkTheme ? "dark" : "light");

    // Cambiar ícono
    themeIcon.classList.remove('fa-sun', 'fa-moon');
    themeIcon.classList.add(isDarkTheme ? 'fa-moon' : 'fa-sun');

    // Cambiar clases del navbar si existe
    if (mainNavbar) {
      mainNavbar.classList.remove('navbar-dark', 'navbar-light', 'bg-dark', 'bg-light');
      mainNavbar.classList.add(isDarkTheme ? 'navbar-dark' : 'navbar-light');
      mainNavbar.classList.add(isDarkTheme ? 'bg-dark' : 'bg-light');
    }
  }

  // Al cargar la página, aplicar el tema
  applyTheme();

  // Al hacer click en el botón, cambiar tema
  themeToggle.addEventListener('click', () => {
    isDarkTheme = !isDarkTheme;
    localStorage.setItem('theme', isDarkTheme ? 'dark' : 'light');
    applyTheme();
  });
  
</script> 
  
</body>
</html>
