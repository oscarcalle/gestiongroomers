<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Sistema de Gestión Veterinaria - Groomers</title>
  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url('assets/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="<?php echo base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="icon" href="<?php echo base_url('assets/favicon.png'); ?>" type="image/x-icon">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="<?php echo base_url('assets/js/bootstrap-select.js'); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  


  <!-- CSS para DataTables y Botones -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css"> -->

<!-- JS para DataTables y Botones -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<!-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>-->

<link href="<?php echo base_url('assets/css/style.css?v=1.0.18'); ?>" rel="stylesheet">

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url('assets/js/jquery.easing.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
</head>


<body id="page-top" translate = "no"> <!-- style="background:#efefef" -->

<div id="loader">
  <div class="icon-background">
    <i class="fa fa-paw spinner"></i>
  </div>
</div>


<div id="loading-backdrop">
  <div id="loading-message">
    Estamos generando tu reporte Excel...
  </div>
</div>

<div class="page-wrapper chiller-theme"> <!--toggled-->
<a id="show-sidebar" class="btn btn-sm btn-dark" href="javascript:void(0)">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <a href="javascript:void(0)"><img class="img-responsive" width="180" src="<?php echo base_url('assets/logo2.png'); ?>" alt="Groomers"></a>
        <div id="close-sidebar">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="sidebar-header">
        <div class="user-pic">
          <img class="img-responsive img-rounded" src="<?php echo base_url('assets/user.png'); ?>" alt="User" >
        </div>
        <div class="user-info">
          <span class="user-name">
            <strong><?php echo $this->session->userdata('role'); ?></strong>
          </span>
          <span class="user-role"><?php echo explode('@', $this->session->userdata('username'))[0]; ?></span>
          <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Conectado</span>
          </span>
        </div>
      </div>
      <!-- sidebar-header  -->
      <div class="sidebar-search">
        <div>
          <div class="input-group">
            <input type="text" class="form-control search-menu" placeholder="Buscar...">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fa fa-search" aria-hidden="true"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- sidebar-search  -->
      <div class="sidebar-menu">
    <ul>
        <?php if (isset($menu) && !empty($menu)): ?>
            <li class="header-menu">
                <span>Menu</span>
            </li>
            <?php foreach ($menu as $item): ?>
                <li class="sidebar-dropdown">
                    <a href="<?= $item['ruta']; ?>">
                        <i class="fa <?= $item['icono']; ?>"></i>
                        <span><?= $item['nombre']; ?></span>
                        <?php if (!empty($item['badge'])): ?>
                            <span class="badge badge-pill bg-info"><?= $item['badge']; ?></span>
                        <?php endif; ?>
                        <?php if (!empty($item['contador'])): ?>
                            <span class="badge badge-pill bg-danger"><?= $item['contador']; ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if (!empty($item['submenus'])): ?>
                        <div class="sidebar-submenu">
                            <ul>
                                <?php foreach ($item['submenus'] as $submenu): ?>
                                    <li>
                                        <a href="<?= $submenu['ruta']; ?>"><?= $submenu['nombre']; ?>
                                            <?php if (!empty($submenu['badge'])): ?>
                                                <span class="badge badge-pill bg-success"><?= $submenu['badge']; ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay elementos de menú disponibles.</p>
        <?php endif; ?>
    </ul>
</div>




      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
      <a href="javascript:void(0)">
        <i class="fa fa-bell"></i>
        <!-- <span class="badge badge-pill badge-warning notification">3</span> -->
      </a>
      <a href="javascript:void(0)">
        <i class="fa fa-envelope"></i>
        <!-- <span class="badge badge-pill badge-success notification">7</span> -->
      </a>
      <a href="javascript:void(0)">
        <i class="fa fa-cog"></i>
        <span class="badge-sonar"></span>
      </a>
      <a href="#" id="openModal" data-bs-toggle="modal" data-target="#logoutModal">
        <i class="fa fa-power-off"></i>
      </a>
    </div>
  </nav>


  