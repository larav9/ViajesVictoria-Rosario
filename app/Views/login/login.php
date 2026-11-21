<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar - Viajes Vic-Ros</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/estilos.css') ?>">
    <link rel="icon" type="image/ico" href="<?= base_url('assets/img/favicon.ico') ?>">

</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Viajes Vic-Ros</h2>
        <p class="subtitulo">Ingresá con tu cuenta para continuar</p>

        <p class="alerta-error" id="login-error" hidden></p> <!--estan ocultos y si hay error o no del controlador auth (json) login.js va a mostrar -->
        <p class="alerta-exito" id="login-exito" hidden></p>

        <form id="form-login" action="<?= base_url('validarLogin') ?>" method="post" data-token-name="<?= csrf_token() ?>">
            <?= csrf_field() ?>
            
            <div class="campo-grupo">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" value="<?= esc(old('usuario')) ?>" required autocomplete="username">
            </div>

            <div class="campo-grupo">
                <label for="clave">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="clave" name="clave" required autocomplete="current-password">
                    <button type="button" class="btn-toggle-pass tachado" id="toggle-clave" aria-label="Mostrar contraseña" title="Mostrar/ocultar contraseña">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                            <line class="linea-tachado" x1="2" y1="2" x2="22" y2="22"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-ingresar">Ingresar</button>
        </form>

        <p class="login-registro">
            ¿No tenés cuenta? <a href="<?= base_url('registro') ?>">Registrate acá</a> · 
            <a href="<?= base_url('panel') ?>" class="link-cartelera">Ver cartelera</a>
        </p>
    </div>

    <img src="<?= base_url('assets/img/paisaje-login.svg') ?>" class="paisaje-mundo" alt="paisaje"><!-- fondo creado con ia:https://share.gemini.google/YiG6FpcObwYj -->
    
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('assets/js/login.js') ?>"></script>
</body>
</html>