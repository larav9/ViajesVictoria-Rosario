<?= $this->include('templates/header') ?>

<div class="reportes-header">
    <h1><?= esc($titulo ?? 'Consultas Recibidas') ?></h1>
    <p class="reportes-subtitulo">Historial de pasajeros que iniciaron contacto por tus viajes.</p>
</div>

<div class="reporte-card">
    <table id="tabla-consultas" class="display" style="width: 100%;">
        <thead>
            <tr>
                <th>Fecha de Consulta</th>
                <th>Viaje</th>
                <th>Pasajero</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consultas as $c): ?>
                <tr>
                    <td data-order="<?= strtotime($c['fecha']) ?>">
                        <?= date('d/m/Y H:i', strtotime($c['fecha'])) ?> hs
                    </td>
                    <td>
                        <?= esc($c['origen']) ?> ➔ <?= esc($c['destino']) ?> (<?= substr(esc($c['hora_salida']), 0, 5) ?> hs)
                    </td>
                    <td>
                        <?= esc(ucwords($c['pasajero_n'] . ' ' . $c['pasajero_a'])) ?>
                        <!--aca tranforma el nombre , las iniciales con mayusc, se ve mas lindo -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('templates/footer') ?>