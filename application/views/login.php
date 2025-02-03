<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Sistema de Gestión Veterinaria - Groomers</title>
    <link rel="icon" href="<?php echo base_url('assets/favicon.png'); ?>" type="image/x-icon">

    <!-- Bootstrap core CSS -->
	<link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Custom styles for Login -->
    <link href="<?php echo base_url('assets/css/login.css'); ?>" rel="stylesheet">

  </head>
  <body translate="no" tabindex="0">

  <form id="loginForm" class="form-signin">
      <div class="text-center mb-4">
        <img src="./assets/logo2.png" alt="logo" width="300">
        <h1 class="h3 mt-5 mb-3 font-weight-normal">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-briefcase-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
		  <path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
		  <path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5v1.384l-7.614 2.03a1.5 1.5 0 0 1-.772 0L0 5.884V4.5zm5-2A1.5 1.5 0 0 1 6.5 1h3A1.5 1.5 0 0 1 11 2.5V3h-1v-.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V3H5v-.5z"/>
		</svg>
          Sistema de Gestión
        </h1>
      </div>

      <div class="form-label-group">
        <input type="text" id="username" name="username" class="form-control" placeholder="Correo Electrónico" required autofocus>
        <label for="username">Email</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
        <label for="password">Password</label>
      </div>

      <!-- <div class="checkbox mb-3">
        <label>
          <input type="checkbox" name="remember" id="remember" value="remember-me"> Recuérdame
        </label>
      </div> -->
      <button class="btn btn-lg btn-success btn-block" type="submit">Ingresar</button>
    </form>

    <footer class="footer mt-auto py-3">
  <div class="container">
    <div class="d-flex justify-content-between">
      <p class="mb-0">&copy; <?php echo date("Y"); ?> .PETMAX SAC</p>
      <p class="mb-0">Created by Liversoft</p>
    </div>
  </div>
</footer>

  </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const queryString = new URLSearchParams(formData).toString(); // Convierte los datos del formulario en una cadena de consulta

    const response = await fetch('<?php echo site_url('login/masuk'); ?>?' + queryString, {
        method: 'GET',  // Usamos GET
    });

    const result = await response.json();
    if (result.success) {
        window.location.href = result.redirect;
    } else {
        Swal.fire('Error', result.message, 'error');
    }
});
    </script>