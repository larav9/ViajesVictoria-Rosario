$(function () {
    // inicializamos datatable
    if ($('#tabla-consultas').length) { //para asegurarse de que la tabla exista en la pantalla actual
        $('#tabla-consultas').DataTable({
            order: [[0, 'desc']], //queria q me muestre orfenada x las mas recientes arriba
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
            }
        });
    }

    // registra consulta por ajax + apertura de wsp en la cartelera
    const $config = $('#config-consultas');

    $('.btn-consultar-ajax').on('click', function () {
        const $boton  = $(this); //del botón tocado, lee el id del viaje y el enlace q arme de wsp
        const idViaje = $boton.data('id-viaje');
        const urlWsp  = $boton.data('wsp');

        // De $config lee la url del controlador (consultas/guardar), el nombre del token y su valor actual 
        // se usa .attr() en lugar de .data() , porque data() guarda el primer valor en caché
        //  y no vería el token nuevo si consultás dos viajes seguidos sin recargar

        const urlDestino  = $config.data('url'); //// Lee la url generada por base_url() desde el html 
        const nombreToken = $config.data('token-name');
        const valorToken  = $config.attr('data-token-hash');

        const textoOriginal = $boton.html(); // guarda todo el html interno del botón en textoOriginal para no perder el icono de wsp
        $boton.prop('disabled', true).text('Registrando...');// lo deshabilita y pone registrando mientras viaja la peticion

        //se arma el obj y usa corchetes (datosEnvio[nombreToken] = valorToken) para asignar dinámicam el nombre exacto del campo csrf
        let datosEnvio = {
            id_viaje: idViaje
        };
        datosEnvio[nombreToken] = valorToken;

        $.ajax({
            url: urlDestino,  //(consultas guardar --- controlador consultas)
            type: 'POST',
            data: datosEnvio, //el obj armado mas arriba
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.token) {
                    $config.attr('data-token-hash', respuesta.token); //se renueva token para q se haga otra nueva consulta (esto me dio error, cuando intentaba consultar nuevam)
                }

                if (respuesta.success) { // si es exitosa abre wsp en otra pestaña
                    window.open(urlWsp, '_blank');
                } else { // sino muestra error
                    alert(respuesta.mensaje || 'No se pudo registrar la consulta.');
                }
            },
            error: function (xhr) { //por problemas de internet mas q nada
                if (xhr.status === 403) {
                    alert('La sesión del formulario venció. Recargá la página e intentá de nuevo.');
                } else {
                    alert('Error de conexión con el servidor al guardar la consulta.');
                }
            },
            complete: function () { // habilitar el botón y restaurar su html original con el ícono y la palabra Consultar
                $boton.prop('disabled', false).html(textoOriginal);
            }
        });
    });
});