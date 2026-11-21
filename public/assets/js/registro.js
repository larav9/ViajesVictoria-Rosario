$(function () { //guardamos si es chofer o no, el telefono y el label del telefono
    const $selectCuenta = $('#tipo_cuenta'); 
    const $inputWsp     = $('#telefono_whatsapp');
    const $labelWsp     = $('#label-whatsapp');

    function actualizarRequisitoWhatsapp() { // si es chofer pone como required el input del wsp y dice q es oblig
        if ($selectCuenta.val() === 'chofer') {
            $inputWsp.prop('required', true);
            $labelWsp.text('Número de WhatsApp (Obligatorio para choferes) *');
        } else {
            $inputWsp.prop('required', false);
            $labelWsp.text('Número de WhatsApp (Opcional para pasajeros)');
        }
    }

    $selectCuenta.on('change', actualizarRequisitoWhatsapp); //acciona cuando se seleecciona el tipo d cuenta
    actualizarRequisitoWhatsapp();// o cuando volves para atras tmb lo vuelve a ejec

    // Mostrar / Ocultar contraseña, tmb le agrega o le saca lo tachado del ojito
    const $claveInput = $('#clave');
    $('#toggle-registro-clave').on('click', function () {
        const esPassword = $claveInput.attr('type') === 'password';
        $claveInput.attr('type', esPassword ? 'text' : 'password');
        $(this).toggleClass('tachado', !esPassword);
    });

    const $form       = $('#form-registro');
    const $error      = $('#registro-error');
    const $exito      = $('#registro-exito');   //capturamos los contenedores, boton,y el token, el botón de envío y el nombre del token
    const $boton      = $form.find('button[type="submit"]');
    const nombreToken = $form.data('token-name');
    const reglaClave  = /^(?=.*[A-Z])(?=.*\d).{8,}$/; //lo mismo q el controlador (8 caract, 1 mayusc y 1 num)

    function mostrarError(texto) {
        $exito.prop('hidden', true); //esconde exito 
        $error.text(texto).prop('hidden', false); //muestra el de error
        $boton.prop('disabled', false); //reabilita el error
    }

    function actualizarToken(token) { //pisa el <input type="hidden"> con el hash nuevo si el registro falla
        if (token) {
            $form.find('input[name="' + nombreToken + '"]').val(token);
        }
    }

    $form.on('submit', function (evento) {
        evento.preventDefault();

        const claveIngresada = $claveInput.val(); //traemos la clave
        if (!reglaClave.test(claveIngresada)) { //prueba si cumple las validaciones, si no le muestra el error
            mostrarError('La contraseña debe tener al menos 8 caracteres, 1 mayúscula y 1 número.');
            $claveInput.focus();
            return; //aca se corta y ahi volves a insertar clave
        }
        //si pasa el filtro, oculta alertas, bloquea el botón paraa evitar envíos duplicados
        $error.prop('hidden', true); 
        $exito.prop('hidden', true);
        $boton.prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST', // lo mandamos por post a regitro/guardar y esperamos resp en json del control auth(registrar)
            data: $form.serialize(), //pasa los valores de un form en una cadena de texto con formato url(campo1=valor1&campo2=valor2)
            dataType: 'json'
        })
        .done(function (respuesta) {
            if (respuesta.success) { //si la resp es exitosa muestra msj de exito , espera 1 seg y va al panel
                $exito.text(respuesta.mensaje).prop('hidden', false);
                setTimeout(function () {
                    window.location.href = respuesta.redirect;
                }, 1000);
                return;
            }

            actualizarToken(respuesta.token); // si false, muestra error y genera token (por ej puede pasar si ya exist el usuario)
            mostrarError(respuesta.mensaje);
        })
        .fail(function (xhr) { //para errores http , sin internet
            if (xhr.status === 403) {
                mostrarError('El formulario venció. Recargá la página e intentá de nuevo.');
            } else {
                mostrarError('No se pudo conectar con el servidor. Intentá de nuevo en unos minutos.');
            }
        });
    });
});