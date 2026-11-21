// logica realizada con ia, para saber como solo filtrar direcciones en las dos prov y demas funciones 
//https://share.gemini.google/BfgHITmrKBzK
document.addEventListener('DOMContentLoaded', function () {
    // verifica que exista el div #mapa en la pagina actual antes de iniciar leaflet para evitar errores en otras vistas
    const contenedorMapa = document.getElementById('mapa');
    if (!contenedorMapa) return;

    const puntosIniciales = [ //no le doy valores iniciales a los dos cuadros
        L.Routing.waypoint(null, ''),
        L.Routing.waypoint(null, '')
    ];

    const mapa = L.map('mapa').setView([-32.7846, -60.4072], 10); // posiciono la vista para q se vea victoria y rosario , sino lo ponia en cualq lado
    mapa.doubleClickZoom.disable(); //el zoom esta desactivado, con dobleclick, porque lo voy a usar para cargar direc

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { //se cargan capas visuales de openstreetmap
        attribution: '© OpenStreetMap'
    }).addTo(mapa);

    const geocoderBase = L.Control.Geocoder.nominatim({   //nominatim hace q se centre en arg 
        geocodingQueryParams: {
            countrycodes: 'ar',
            viewbox: '-60.85,-33.15,-59.95,-32.45', //esto hace q no se vaya del rango visual q queremos
            bounded: 0
        }
    });

    //esta funcion limpia a solo calle y altura (antes me traia otros atributos inncesearios como distritos)
    // recibe la cadena completa que devuelve nominatim separada por comas y la reduce a lo esencial
    function limpiarNombre(textoCompleto) {
        if (!textoCompleto) return '';
        // divide el texto por comas y quita espacios en blanco alrededor de cada parte
        const partes = textoCompleto.split(',').map(p => p.trim());
        // si ya viene corto (2 partes o menos), lo devuelve tal cual
        if (partes.length <= 2) return textoCompleto;

        // descarta divisiones administrativas, provincias, pais y codigos postales (ej: S2000 o 3153)
        const utiles = partes.filter(p =>
            !p.startsWith('Distrito') &&
            !p.startsWith('Departamento') &&
            !p.startsWith('Municipio') &&
            !p.startsWith('Gran ') &&
            !p.includes('Cuartel') &&
            p !== 'Entre Ríos' &&
            p !== 'Santa Fe' &&
            p !== 'Argentina' &&
            !/^[A-Z]?\d{4}[A-Z]{0,3}$/.test(p)
        );

        // toma el ultimo elemento util como localidad y los dos primeros como calle y altura
        const ciudad = utiles[utiles.length - 1] || '';
        const primeros = utiles.slice(0, 2).join(', ');
        // arma el formato final "calle, altura, ciudad" evitando repetir la ciudad si ya venia incluida
        return (ciudad && !primeros.includes(ciudad)) ? `${primeros}, ${ciudad}` : primeros;
        //solo necesito calle altura y localidad
    }

    // envoltorio personalizado sobre nominatim para interceptar las busquedas y limpiar los textos antes de mostrarlos
    const geocoderLimpio = { //esto es para q si estas muy alejado el mapa igual tome la calle exacta y direc
        // se ejecuta cuando el usuario escribe una direccion en los inputs y presiona enter
        geocode: function (query, cb, context) {
            geocoderBase.geocode(query, function (results) {
                // recorre cada coincidencia encontrada y le limpia el nombre con limpiarNombre()
                const filtrados = (results || []).map(r => {
                    r.name = limpiarNombre(r.name);
                    return r;
                });
                cb.call(context, filtrados);
            });
        },
        // geocodificacion inversa: traduce coordenadas (lat/lng) a una direccion escrita al hacer doble clic o arrastrar un marcador
        reverse: function (location, scale, cb, context) {
            // Forzamos escala de zoom 18 (nivel calle/casa) en lugar del zoom alejado del mapa
            const escalaDetalle = mapa.options.crs.scale(18);
            geocoderBase.reverse(location, escalaDetalle, function (results) {
                const filtrados = (results || []).map(r => {
                    r.name = limpiarNombre(r.name);
                    return r;
                });
                cb.call(context, filtrados);
            });
        }
    };

    // inicializa el motor de rutas de leaflet routing machine sobre el mapa -- https://share.gemini.google/5kry1pIsV2t3
    const controlRuta = L.Routing.control({
        waypoints: puntosIniciales,
        routeWhileDragging: false, // recalcula el recorrido en tiempo real mientras se arrastra un pin
        geocoder: geocoderLimpio, // usa nuestro geocoder filtrado en lugar del por defecto
        language: 'es',
        showAlternatives: false, // oculta rutas alternativas para mantener el mapa limpio
        addWaypoints: true, // permite sumar paradas intermedias haciendo clic sobre la linea violeta
        lineOptions: {
            styles: [{ color: '#a855f7', opacity: 0.9, weight: 5 }] // estilo violeta acorde a la paleta del sistema
        }
    }).addTo(mapa);

    // referencias a los contenedores html del panel izquierdo y del desplegable de indicaciones
    const panelDirecciones = document.getElementById('panel-direcciones');
    const contenedorPasos = document.getElementById('contenedor-pasos-ruta');
    const routingContainer = controlRuta.getContainer();

    // saca la caja de inputs que leaflet crea flotando sobre el mapa y la mete dentro de #panel-direcciones
    if (panelDirecciones) {
        panelDirecciones.appendChild(routingContainer);
    }

    // Mueve la tabla de instrucciones giro a giro dentro del desplegable <details>
    // evita que las indicaciones largas estiren el panel lateral izquierdo
    function moverIndicacionesAlDesplegable() {
        if (!contenedorPasos) return;
        const tablaPasos = routingContainer.querySelector('.leaflet-routing-alternatives-container');
        if (tablaPasos && tablaPasos.parentElement !== contenedorPasos) {
            contenedorPasos.appendChild(tablaPasos);
        }
    }

    // recorre todos los inputs de direcciones activos y les asigna el texto guia segun su posicion (0 = partida, 1+ = paradas)
    function actualizarPlaceholders() {
        if (!panelDirecciones) return;
        const inputs = panelDirecciones.querySelectorAll('.leaflet-routing-geocoders input');
        inputs.forEach((input, idx) => {
            input.placeholder = idx === 0
                ? 'Partida (Escribi calle altura y localidad y presioná Enter)'
                : `Parada ${idx} (escribí y presioná Enter)`;
        });
    }

    // Ordena automáticamente todas las paradas respecto al punto de partida (índice 0)
    // bandera de control para evitar que setWaypoints() vuelva a disparar este mismo evento en bucle infinito
    let reordenando = false;

    // aplica el algoritmo del vecino mas cercano para ordenar las paradas de menor a mayor distancia desde la salida
    function optimizarAutomaticamente() {
        if (reordenando) return;

        const todosWps = controlRuta.getWaypoints();
        // el primer punto (indice 0) queda fijo como punto de partida
        const origen = todosWps[0];
        if (!origen || !origen.latLng) return;

        // separa las paradas que ya tienen coordenadas cargadas de los inputs que quedaron vacios
        const paradasValidas = todosWps.slice(1).filter(wp => wp && wp.latLng);
        const paradasVacias = todosWps.slice(1).filter(wp => !wp || !wp.latLng);

        // si hay menos de 2 paradas cargadas no tiene sentido reordenar
        if (paradasValidas.length < 2) return;

        let pendientes = [...paradasValidas];
        const ordenadas = [];
        let actual = origen;

        // en cada vuelta busca cual de las paradas pendientes queda mas cerca en metros del ultimo punto elegido
        while (pendientes.length > 0) {
            let mejorIndice = 0;
            let menorDistancia = Infinity;

            pendientes.forEach((candidata, idx) => {
                const dist = actual.latLng.distanceTo(candidata.latLng);
                if (dist < menorDistancia) {
                    menorDistancia = dist;
                    mejorIndice = idx;
                }
            });

            // saca la parada mas cercana de pendientes, la agrega a ordenadas y pasa a ser el nuevo punto de referencia
            const elegida = pendientes.splice(mejorIndice, 1)[0];
            ordenadas.push(elegida);
            actual = elegida;
        }

        // Solo actualiza si el orden cambió respecto al actual
        const cambioOrden = ordenadas.some((wp, i) => wp.latLng !== paradasValidas[i].latLng);
        if (cambioOrden) {
            reordenando = true;
            // reescribe los puntos en el mapa respetando el origen primero, las paradas ordenadas y los inputs vacios al final
            controlRuta.setWaypoints([origen, ...ordenadas, ...paradasVacias]);
            reordenando = false;
        }
    }

    // Dibuja el cuadro 1°, 2°, 3°... y actualiza métricas
    // recibe opcionalmente el objeto rutaEncontrada cuando leaflet termina de calcular distancia y duracion
    function actualizarCuadroOrden(rutaEncontrada) {
        actualizarPlaceholders();
        moverIndicacionesAlDesplegable();

        const lista = document.getElementById('lista-orden-viaje');
        const metricas = document.getElementById('ruta-metricas');
        if (!lista) return;

        // filtra solo los puntos que ya tienen ubicacion real en el mapa
        const wpsValidos = controlRuta.getWaypoints().filter(wp => wp && wp.latLng);
        lista.innerHTML = '';

        // si todavia no hay al menos salida y una parada, muestra el mensaje de ayuda inicial
        if (wpsValidos.length < 2) {
            lista.innerHTML = '<li style="color: var(--texto-secundario);">Cargá el punto de partida y al menos una parada.</li>';
            if (metricas) metricas.textContent = '';
            return;
        }

        // genera cada item visual numerado (1°, 2°, 3°...) diferenciando el origen, paradas intermedias y ultima parada
        wpsValidos.forEach((wp, i) => {
            const li = document.createElement('li');
            const esPrimero = i === 0;
            const esUltimo = i === wpsValidos.length - 1;
            const etiquetaOrden = `${i + 1}°`;
            const rol = esPrimero ? 'Punto de partida' : (esUltimo ? 'Última parada' : 'Parada');
            // usa el nombre limpio de la direccion o, si no resolvio calle, muestra latitud y longitud
            const direccion = wp.name || `${wp.latLng.lat.toFixed(4)}, ${wp.latLng.lng.toFixed(4)}`;

            li.innerHTML = `
                <span class="orden-numero ${esUltimo ? 'destino' : ''}">${etiquetaOrden}</span>
                <span><strong>${rol}:</strong> ${direccion}</span>
            `;
            lista.appendChild(li);
        });

        // si ya se calculo la ruta por calles, convierte metros a km y segundos a horas/minutos
        if (rutaEncontrada && metricas) {
            const km = (rutaEncontrada.summary.totalDistance / 1000).toFixed(1);
            const mins = Math.round(rutaEncontrada.summary.totalTime / 60);
            const horas = Math.floor(mins / 60);
            const minsResto = mins % 60;
            const textoTiempo = horas > 0 ? `${horas}h ${minsResto}m` : `${mins} min`;
            metricas.textContent = `${km} km · ${textoTiempo}`;
        }
    }

    // ejecuta el render inicial para dejar los placeholders y el mensaje por defecto listos al cargar la pagina
    actualizarCuadroOrden(null);

    // escucha cada vez que se agrega, borra, mueve o edita un punto en el mapa para reordenar y refrescar la lista
    controlRuta.on('waypointschanged', function () {
        optimizarAutomaticamente();
        actualizarCuadroOrden(null);
    });

    // escucha cuando el servicio de ruteo termina de trazar el camino y devuelve la distancia y el tiempo total
    controlRuta.on('routesfound', function (e) {
        actualizarCuadroOrden(e.routes[0]);
    });

    // Doble clic en el mapa -> llena el primer campo vacío o agrega una parada nueva
    mapa.on('dblclick', function (e) {
        const wps = controlRuta.getWaypoints();
        // busca si quedo algun input sin completar (por ejemplo el de partida o el de una parada agregada)
        const indiceVacio = wps.findIndex(wp => !wp.latLng);

        // busca la direccion escrita correspondiente a las coordenadas donde el usuario hizo doble clic
        geocoderLimpio.reverse(e.latlng, mapa.options.crs.scale(mapa.getZoom()), function (results) {
            const nombreLugar = (results && results.length > 0) ? results[0].name : '';
            const nuevoPunto = { latLng: e.latlng, name: nombreLugar };

            if (indiceVacio !== -1) {
                // reemplaza el primer casillero vacio con el punto marcado
                controlRuta.spliceWaypoints(indiceVacio, 1, nuevoPunto);
            } else {
                // si todos los casilleros ya estaban ocupados, agrega una parada nueva al final
                controlRuta.spliceWaypoints(wps.length, 0, nuevoPunto);
            }
        });
    });

    // Botón "+ Agregar parada"
    // inserta un waypoint vacio al final de la lista para habilitar un nuevo campo de texto en el panel
    const btnAgregar = document.getElementById('btn-agregar-parada');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', function () {
            const wps = controlRuta.getWaypoints();
            controlRuta.spliceWaypoints(wps.length, 0, null);
        });
    }

});