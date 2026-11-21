$(function () {
    const $modalPerfil = $('#modal-perfil'); //traemos modal, el perfil y el ojo para tacharlo o no
    const $perfilClave = $('#perfil-clave');
    const $btnOjo      = $('#toggle-perfil-clave');

    function cerrarModalPerfil() {
// Cierra el modal, vuelve a ocultar la contraseña y reinicia el ícono del ojo tachado
        $modalPerfil.removeClass('activo');
        $perfilClave.attr('type', 'password');
        $btnOjo.addClass('tachado');
    }

    $('#btn-abrir-perfil').on('click', function (e) {
        e.preventDefault();
        $modalPerfil.addClass('activo'); // se activa el model con el click
    });

    $('#btn-cerrar-perfil').on('click', cerrarModalPerfil); // se cierra

    $modalPerfil.on('click', function (e) { // se cierra modal solo si se hace clic en el fondo 
        if (e.target === this) {
            cerrarModalPerfil();
        }
    });

    $btnOjo.on('click', function () { //si queres mostrar o no la contra (lo hicimos igual en registro)
        const esPassword = $perfilClave.attr('type') === 'password';
        $perfilClave.attr('type', esPassword ? 'text' : 'password');
        $(this).toggleClass('tachado', !esPassword);
    });

    if ($('.alerta-flash').length) {
        setTimeout(function () {
            $('.alerta-flash').fadeOut(300);   //le damoss 4 seg a la alerta y q se desvanezca 
        }, 4000);
    }
});