<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'Viajes Vic-Ros') ?></title>

    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/estilos.css') ?>">
</head>
<body>

<div class="dashboard-layout"> <!-- ver clases y estilos en estilos.css -->
    <aside class="sidebar">
        <div class="sidebar-logo">Viajes Vic-Ros</div>

        <?php if (session()->get('logueado')): ?>  <!-- si esta log muestra su nombre y rol, sino muestra las opc para ing o regist -->
            <div class="sidebar-usuario">
                <span class="usuario-nombre"><?= esc(session()->get('nombre')) ?></span>
                <span class="usuario-rol"><?= esc(ucfirst(session()->get('rol'))) ?></span>  <!-- ucfirst pasa a mayusc la primer letra, dejando el resto igual -->
            </div>
        <?php else: ?>
            <div class="sidebar-invitado"> 
                <p>Iniciá sesión para contactar choferes o publicar viajes.</p>
                <a href="<?= base_url('login') ?>" class="btn-sidebar-login">Iniciar Sesión</a>
                <a href="<?= base_url('registro') ?>" class="link-sidebar-registro">Crear cuenta</a>
            </div>
        <?php endif; ?>

        <nav class="sidebar-nav">
            <!-- url_is() detecta la ruta actual y, si coincide, suma la clase 'activo' para resaltar el botón en el menu-->
            <a href="<?= base_url('panel') ?>" class="nav-item <?= (url_is('panel') || url_is('/')) ? 'activo' : '' ?>">Cartelera</a>
            
            <?php if (session()->get('logueado')): ?>
                
                <a href="#" id="btn-abrir-perfil" class="nav-item">Configurar perfil</a> 
                <!-- se abre el modal para editar datos del perfil-, el model esta en el footer-->
                <?php if (session()->get('rol') === 'chofer'): ?>
                    <a href="<?= base_url('chofer/gestion') ?>" class="nav-item <?= url_is('chofer/gestion') ? 'activo' : '' ?>">Mi Gestión</a>
                    <a href="<?= base_url('consultas') ?>" class="nav-item <?= url_is('consultas') ? 'activo' : '' ?>">Consultas</a>
                    <a href="<?= base_url('reportes') ?>" class="nav-item <?= url_is('reportes') ? 'activo' : '' ?>">Reportes</a>


                <?php endif; ?>
                <a href="<?= base_url('logout') ?>" class="nav-item nav-logout" onclick="return confirm('¿Desea salir?')">Cerrar Sesión</a>
            <?php endif; ?>
        </nav>
    </aside>

    <main class="main-content">
        <!-- cuando se recarga la pag, header.php pregunta con el if si llegó algún mensaje de 'error' 
        o 'exito'(config en el controlador Perfil), si existe, dibuja el <p> con su clase CSS y lo imprime protegido por esc().
        Si apretás F5 de nuevo, el cartel desaparece  porque ya se consumió. -->
        <?php if (session()->getFlashdata('error')): ?>
            <p class="alerta-error"><?= esc(session()->getFlashdata('error')) ?></p>
        <?php endif; ?>
        <?php if (session()->getFlashdata('exito')): ?>
            <p class="alerta-exito"><?= esc(session()->getFlashdata('exito')) ?></p>
        <?php endif; ?>