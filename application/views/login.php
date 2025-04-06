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

    <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom styles for Login -->
    <link href="<?php echo base_url('assets/css/login.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/login/bootstrap.min.css'); ?>" rel="stylesheet">

  </head>

  <body translate="no" tabindex="0">

<div class="container-fluid ps-md-0" >
    <div class="row g-0">
      <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
      <div class="col-md-8 col-lg-6">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-8 mx-auto">
                
                <div class="text-center">
                  <img src="./assets/logo.png" alt="" width="400px" style="margin-bottom: 25px;">
                  <h3 class="login-heading mb-4">Sistema de Gestión Veterinaria</h3>
                </div>
  
                <!-- Sign In Form -->
                <form  id="loginForm">
                  <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="username" name="username" placeholder="name@example.com" required>
                    <label for="floatingInput">Correo electrónico</label>
                  </div>
                  <div class="form-floating mb-3 input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <label for="password">Contraseña</label>
                    <button type="button" class="input-group-text" id="togglePassword" style="cursor:pointer;">
                      <i class="fa fa-eye" id="iconEye"></i>
                    </button>
                  </div>

                  <div class="d-grid">
                    <button class="btn btn-lg btn-primary btn-login mb-2" type="submit"><i class="fa fa-arrow-right"></i> Iniciar sesión</button>
                    <div class="text-center">
                      <a class="small" href="#" class="link"> Regístrate</a>
                      &nbsp;&nbsp;|&nbsp;&nbsp;
                    <a class="small" href="#" class="link">¿Olvidaste tu contraseña?</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

  </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const loginButton = document.querySelector('button[type="submit"]');
    loginButton.disabled = true;
    loginButton.textContent = 'Validando credenciales...';

    const formData = new FormData(this);
    const queryString = new URLSearchParams(formData).toString();

    try {
        const response = await fetch('<?php echo site_url('login/masuk'); ?>?' + queryString, {
            method: 'GET',
        });

        const result = await response.json();
        
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            Swal.fire('Error', result.message, 'error');
            loginButton.disabled = false;
            loginButton.textContent = 'Ingresar';
        }
    } catch (error) {
        Swal.fire('Error', 'Ocurrió un problema al procesar la solicitud', 'error');
        loginButton.disabled = false;
        loginButton.textContent = 'Ingresar';
    }
});

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('iconEye');

    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
});

</script>
