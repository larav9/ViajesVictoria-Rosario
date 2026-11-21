<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Viajes Vic-Ros</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/estilos.css') ?>">
    <link rel="icon" type="image/ico" href="<?= base_url('assets/img/favicon.ico') ?>">

</head>
<body>

<div class="login-wrapper">
    <div class="login-card registro-card">
        <h2>Viajes Vic-Ros</h2>
        <p class="subtitulo">Creá tu cuenta para publicar viajes o contactar choferes</p>

        <p class="alerta-error" id="registro-error" hidden></p>
        <p class="alerta-exito" id="registro-exito" hidden></p>

        <form id="form-registro" action="<?= base_url('registro/guardar') ?>" method="post" data-token-name="<?= csrf_token() ?>">
            <?= csrf_field() ?>

            <div class="registro-fila">
                <div class="campo-grupo">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="campo-grupo">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" id="apellido" required>
                </div>
            </div>

            <div class="registro-fila">
                <div class="campo-grupo">
                    <label for="usuario">Usuario (para ingresar)</label>
                    <input type="text" name="usuario" id="usuario" required autocomplete="username">
                </div>
            <div class="campo-grupo">
                <label for="clave">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" name="clave" id="clave" minlength="8" pattern="(?=.*[A-Z])(?=.*\d).{8,}" required autocomplete="new-password">
                    <button type="button" class="btn-toggle-pass tachado" id="toggle-registro-clave" aria-label="Mostrar contraseña" title="Mostrar/ocultar contraseña">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                            <line class="linea-tachado" x1="2" y1="2" x2="22" y2="22"></line>
                        </svg>
                    </button>
                </div>
            </div>
            </div>

            <div class="registro-aviso-clave">
                La contraseña debe tener mínimo 8 caracteres, al menos 1 letra mayúscula y 1 número.
            </div>

            <div class="campo-grupo registro-campo">
                <label for="email">Email (Opcional)</label>
                <input type="email" name="email" id="email" placeholder="ejemplo@correo.com">
            </div>

            <div class="campo-grupo registro-campo">
                <label for="tipo_cuenta">¿Cómo vas a usar la plataforma?</label>
                <select name="tipo_cuenta" id="tipo_cuenta" required> <!-- si es chofer obliga desp a pedir telefono-->
                    <option value="pasajero">Soy Pasajero (Quiero buscar viajes)</option>
                    <option value="chofer">Soy Chofer (Quiero publicar viajes)</option>
                </select>
            </div>

            <div class="campo-grupo registro-whatsapp-box">
                <label for="telefono_whatsapp" id="label-whatsapp">Número de WhatsApp (Opcional para pasajeros)</label>
                <small class="registro-ayuda">
                    Ingresá solo números con código de país (54) y área sin 15. Ej: 5493436123456.
                </small>
                <input type="number" name="telefono_whatsapp" id="telefono_whatsapp" placeholder="5493436123456">
            </div>

            <button type="submit" class="btn-ingresar">Registrarme</button>
        </form>

        <p class="login-registro">
            ¿Ya tenés cuenta? <a href="<?= base_url('login') ?>">Iniciá sesión acá</a> · 
            <a href="<?= base_url('panel') ?>" class="link-cartelera">Ver cartelera</a>
        </p>
    </div>

    <img src="<?= base_url('assets/img/paisaje-login.svg') ?>" class="paisaje-mundo" alt=""> <!--este svg lo hice con la ia, en la documentacion esta la pagina donde me inspire el diseño-->
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('assets/js/registro.js') ?>"></script>
</body>
</html>