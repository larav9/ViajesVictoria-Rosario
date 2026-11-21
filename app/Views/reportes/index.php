<div class="reportes-header">
    <h1>Reportes</h1>
</div>
<!--
    - #datos-reportes: div oculto que pasa los arreglos json de php a js mediante atributos data-* (usa comillas simples y esc(..., 'attr')
    - para escapar el atributo sin romper las comillas dobles del json).
-->

<div id="datos-reportes"
     data-labels-dias='<?= esc($labelsDias, 'attr') ?>'
     data-valores-dias='<?= esc($valoresDias, 'attr') ?>'
     data-labels-horarios='<?= esc($labelsHorarios, 'attr') ?>'
     data-valores-horarios='<?= esc($valoresHorarios, 'attr') ?>'
     hidden></div>

<div class="reportes-grid">
<!--se van a mostrar 2 cards , uno para los dias y otro para las horas , o sea los canvas -->
    
    <div class="reporte-card">
        <h3>Consultas por Día de la Semana</h3>
        <canvas id="graficoDias"></canvas>
    </div>

    <div class="reporte-card">
        <h3>Horarios de Salida más Elegidos</h3>
        <canvas id="graficoHorarios"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- el cdn de chart-->
<script src="<?= base_url('assets/js/reportes.js') ?>"></script>