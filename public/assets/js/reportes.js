// para realizarlo consulte primero documentacion para solo mostrar graficos en barra :https://www.chartjs.org/docs/latest/charts/bar.html
// luego tmb me asisti con ia para saber como armar esos array de dias y horas en el controlador 
document.addEventListener('DOMContentLoaded', function () {
    const contenedorDatos = document.getElementById('datos-reportes');
    if (!contenedorDatos) return;

// Lee los cuatro atributos guardados en el <div> usando la API nativa .dataset
//  js conviernte los nombre html (data-labels-dias) a (contenedorDatos.dataset.labelsDias).
    const labelsDias      = JSON.parse(contenedorDatos.dataset.labelsDias || '[]'); // como vienen como text plano lo pasamos a obj
    const valoresDias     = JSON.parse(contenedorDatos.dataset.valoresDias || '[]');// se hacen arrays 
    const labelsHorarios  = JSON.parse(contenedorDatos.dataset.labelsHorarios || '[]');
    const valoresHorarios = JSON.parse(contenedorDatos.dataset.valoresHorarios || '[]');

    const opcionesComunes = { //para los dos graf
        responsive: true, // son responsive
        plugins: {
            legend: { display: false } //oculto la leynda, porque le di sus titulos
        },
        scales: {
            y: {
                beginAtZero: true, // siempre el eje empieza en 0
                ticks: { stepSize: 1 } // es la escala , o sea 1 consulta , 2 consultas etc
            }
        }
    };

    const canvasDias = document.getElementById('graficoDias'); // traemos el grafico dias 
    if (canvasDias) {
        new Chart(canvasDias, { // se instancia chart y se pasan los datos (los arrays de arriba)
            type: 'bar',
            data: {
                labels: labelsDias,
                datasets: [{
                    label: 'Consultas',
                    data: valoresDias,
                    backgroundColor: '#7c3aed',
                    borderRadius: 6
                }]
            },
            options: opcionesComunes
        });
    }

    const canvasHorarios = document.getElementById('graficoHorarios'); //traemos el grafico horarios
    if (canvasHorarios) {
        new Chart(canvasHorarios, {
            type: 'bar',
            data: {
                labels: labelsHorarios,
                datasets: [{
                    label: 'Consultas',
                    data: valoresHorarios,
                    backgroundColor: '#a855f7',
                    borderRadius: 6
                }]
            },
            options: opcionesComunes
        });
    }
});