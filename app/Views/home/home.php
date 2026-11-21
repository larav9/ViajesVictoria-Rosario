<?php /* NOTA IA: generado con asistencia de IA. Link: <PEGAR AQUÍ EL LINK> */ ?>
<section class="tarjeta">
    <h1>Viajes Victoria - Rosario</h1>

    <p>
        Encontrá los viajes que ofrecen los choferes de la ciudad, con días, horarios
        y lugares disponibles, y consultales directamente por WhatsApp.
    </p>

    <?php if (!session()->get('logueado')): ?>
        <p>
            <a class="boton" href="<?= base_url('login') ?>">Ingresar</a>
            <a class="boton secundario" href="<?= base_url('registro') ?>">Registrarme</a>
        </p>
    <?php else: ?>
        <p><a class="boton" href="<?= base_url('panel') ?>">Ir a mi panel</a></p>
    <?php endif; ?>
</section>
