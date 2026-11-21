$(function () {
    //para usarlas desp sin repetir asignamos los jquery 
    const $form         = $('#form-login');
    const $usuarioInput = $('#usuario');
    const $claveInput   = $('#clave');
    const $error        = $('#login-error');
    const $exito        = $('#login-exito');
    const $boton        = $form.find('button[type="submit"]');
    const nombreToken   = $form.data('token-name'); 
    //lee nombreToken desde data-token-name del form para saber como se llama el <input type="hidden"> del token csrf de codeigniter

    // Mostrar / Ocultar contraseña con el ojito
    //cuando se clickea el ojo, revisa si el input tiene type="password" (esPassword).
    $('#toggle-clave').on('click', function () {
        const esPassword = $claveInput.attr('type') === 'password';
        $claveInput.attr('type', esPassword ? 'text' : 'password');
        $(this).toggleClass('tachado', !esPassword);    // si estaba oculta, la pasa a type="text" para que se lea; si estaba visible, vuelve a type="password"
    });

    function mostrarError(texto, usuarioGuardado) {
        // oculta el cartel verde por si había quedado visible, vuelve a escribir el usuario si el 
        // servidor lo devolvió, vacía el campo de contraseña y le pone el cursor 
        // adentro con focus, muestra el texto de error quitando hidden (prop('hidden', false)) y 
        // vuelve a habilitar el botón de ingresar.
        $exito.prop('hidden', true);
        if (usuarioGuardado) {
            $usuarioInput.val(usuarioGuardado);
        }
        $claveInput.val('').focus();
        $error.text(texto).prop('hidden', false);
        $boton.prop('disabled', false);
    }

    function actualizarToken(token) {
        // codeigniter renueva el token CSRF cada vez que recibe un post, si el usuario le erra a la clave y
        //  vuelve a intentar sin recargar la página, necesita el token nuevo!!
        // la función busca el input oculto por su nombre y le pisa el .val() con el hash nuevo
        if (token) {
            $form.find('input[name="' + nombreToken + '"]').val(token);
        }
    }

    $form.on('submit', function (evento) {
        evento.preventDefault();
        // oculta alertas anteriores y deshabilita el botón para evitar que el usuario haga 2 clic y mande 2 petic seguidas
        $error.prop('hidden', true);
        $exito.prop('hidden', true);
        $boton.prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(), //convierte los campos de un form html en un string codificada para url
            dataType: 'json' //aca le decimos q espera un json de Auth (validarlogin)
        })
        .done(function (respuesta) { // si es succes,da la bienvenida y  espera 1 segundo para redirigir 
            if (respuesta.success) {
                $exito.text(respuesta.mensaje).prop('hidden', false);
                setTimeout(function () {
                    window.location.href = respuesta.redirect;
                }, 1000);
                return;
            }
                //si es false act el token y muestra el error
            actualizarToken(respuesta.token);
            mostrarError(respuesta.mensaje, respuesta.usuario);
        })
        .fail(function (xhr) { 
            if (xhr.status === 403) { //avisa que venció el form (token) y para cualq otro fallo avisa que hubo un prob de conexión
                mostrarError('El formulario venció. Recargá la página.');
            } else {
                mostrarError('No se pudo conectar con el servidor. Intentá de nuevo en unos minutos.');
            }
        });
    });
});