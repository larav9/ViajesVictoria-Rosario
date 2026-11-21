<div class="ruta-header">
    <div>
        <h1>Visualiza tus rutas </h1>
        <p>Cargá tu punto de partida y las paradas (con <strong>Enter</strong> 
        o <strong>doble clic en el mapa</strong>). El orden óptimo se calcula solo.</p>
    </div>
</div>

<div class="tarjeta-formulario">
    <div class="ruta-grid">
        <!--  Inputs, orden óptimo automático y desplegable opcional -->
        <div class="ruta-panel-izq">
            <div class="ruta-controles-top">
                <span>Direcciones</span>
                <button type="button" id="btn-agregar-parada" class="btn-parada">+ Agregar parada</button>
            </div>

            <div id="panel-direcciones"></div>

            <!-- orden optimo-->
            <div id="cuadro-orden-optimo" class="ruta-orden-caja">
                <div class="ruta-orden-header">
                    <strong>Orden de recorrido recomendado</strong>
                    <span id="ruta-metricas" class="badge badge-diario"></span>
                </div>
                <ol id="lista-orden-viaje" class="ruta-orden-lista"></ol>
            </div>

            <!-- desplegable opcional-->
            <details class="ruta-indicaciones-desplegable">
                <summary>Ver indicaciones paso a paso</summary>
                <div id="contenedor-pasos-ruta"></div>
            </details>
        </div>

        <!-- mapita-->
        <div>
            <div id="mapa"></div>
        </div>
    </div>
</div>

<!-- librerias externas de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>


<script src="<?= base_url('assets/js/mapa.js?v=7') ?>"></script>