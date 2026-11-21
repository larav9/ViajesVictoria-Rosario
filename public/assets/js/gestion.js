document.addEventListener('DOMContentLoaded', () => {
    
    //  va tomando cuando vas moviendote de pestaña(toma el click), y le pone activo ()
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('activo'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('activo'));
            
            this.classList.add('activo');
            const target = this.getAttribute('data-target');
            document.getElementById(target).classList.add('activo');
        });
    });

    // abrir modales de alta (+ Publicar Viaje, + Agregar Vehículo, + Agregar Zona)
    document.querySelectorAll('.btn-abrir-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            //ve cual de los modales se selecciona y le da la clase activo
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('activo');
        });
    });

    //abrir modal edicion zonas
    document.querySelectorAll('.btn-edit-zona').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('mod-zona-id').value = this.getAttribute('data-id');
            document.getElementById('mod-zona-nombre').value = this.getAttribute('data-nombre');
            document.getElementById('modal-zona').classList.add('activo');
        });
    });

    //abrir modal edicion vehiculos (lo mismo)
    document.querySelectorAll('.btn-edit-vehiculo').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('mod-veh-id').value = this.getAttribute('data-id');
            document.getElementById('mod-veh-marca').value = this.getAttribute('data-marca');
            document.getElementById('mod-veh-modelo').value = this.getAttribute('data-modelo');
            document.getElementById('mod-veh-anio').value = this.getAttribute('data-anio');
            document.getElementById('mod-veh-color').value = this.getAttribute('data-color');
            document.getElementById('mod-veh-pasajeros').value = this.getAttribute('data-pasajeros');
            document.getElementById('mod-veh-seguro').value = this.getAttribute('data-seguro');
            document.getElementById('mod-veh-aseguradora').value = this.getAttribute('data-aseguradora');
            document.getElementById('mod-veh-vencimiento').value = this.getAttribute('data-vencimiento');
            
            document.getElementById('modal-vehiculo').classList.add('activo');
        });
    });

    // abrir modal edicion de viajes
    document.querySelectorAll('.btn-edit-viaje').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('mod-viaje-id').value = this.getAttribute('data-id');
            document.getElementById('mod-viaje-vehiculo').value = this.getAttribute('data-vehiculo');
            document.getElementById('mod-viaje-origen').value = this.getAttribute('data-origen');
            document.getElementById('mod-viaje-destino').value = this.getAttribute('data-destino');
            
            const tipo = this.getAttribute('data-tipo');
            const selectTipo = document.getElementById('mod-select-tipo-viaje'); //aca trae si se selecciono diaros o eventual
            selectTipo.value = tipo;

            const inputFecha = document.getElementById('mod-input-fecha-viaje');
            const grupoDias = document.getElementById('mod-grupo-dias');

            if (tipo === 'eventual') {
                inputFecha.disabled = false;
                inputFecha.value = this.getAttribute('data-fecha');
                grupoDias.style.display = 'none'; //oculta los dias si elegis eventual
            } else {
                inputFecha.disabled = true;
                inputFecha.value = '';
                grupoDias.style.display = 'block'; //sino lo muestra
            }

            document.getElementById('mod-viaje-hora').value = this.getAttribute('data-hora');
            document.getElementById('mod-viaje-capacidad').value = this.getAttribute('data-capacidad');
            document.getElementById('mod-viaje-precio').value = this.getAttribute('data-precio');
            document.getElementById('mod-viaje-obs').value = this.getAttribute('data-obs');

            //convierte el texto json de data-dias (ej. '["Lunes","Viernes"]') en un arreglo de js 
            //desp recorre todos los checkboxes .mod-chk-dia y marca cada uno (chk.checked = true) si su valor está incluido 
            //en ese arreglo (diasViaje.includes(chk.value)
            const diasViaje = JSON.parse(this.getAttribute('data-dias') || '[]'); //pasamos a objeto el json data dias
            document.querySelectorAll('.mod-chk-dia').forEach(chk => {
                chk.checked = diasViaje.includes(chk.value); 
            });
            // Primero desmarca todas las zonas y vacía recargos para limpiar datos de un viaje abierto antes
            document.querySelectorAll('.mod-chk-zona').forEach(chk => {
                chk.checked = false;
                const inputRecargo = document.getElementById('mod-recargo-' + chk.value);
                if (inputRecargo) inputRecargo.value = '';
            });
            // Convierte el JSON de zonas del viaje y tilda las que correspondan con su recargo
            const zonasViaje = JSON.parse(this.getAttribute('data-zonas') || '[]');
            zonasViaje.forEach(z => {
                const chk = document.querySelector(`.mod-chk-zona[value="${z.id_zona}"]`);
                if (chk) {
                    chk.checked = true;
                    const inputRecargo = document.getElementById('mod-recargo-' + z.id_zona);
                    if (inputRecargo) inputRecargo.value = z.recargo;
                }
            });

            document.getElementById('modal-viaje').classList.add('activo');
        });
    });

    // Control dinámico de tipo de viaje en el modal de edición
    const modSelectTipo = document.getElementById('mod-select-tipo-viaje');
    if (modSelectTipo) {
        modSelectTipo.addEventListener('change', function() {
            const inputFecha = document.getElementById('mod-input-fecha-viaje');
            const grupoDias = document.getElementById('mod-grupo-dias');
            if (this.value === 'eventual') {
                inputFecha.disabled = false;
                grupoDias.style.display = 'none';
            } else {
                inputFecha.disabled = true;
                inputFecha.value = '';
                grupoDias.style.display = 'block';
            }
        });
    }

    // Cerrar cualquier Modal con la "X" o haciendo clic fuera del cuadro
    document.querySelectorAll('.btn-cerrar-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            document.getElementById(modalId).classList.remove('activo'); //le saca la clase activo 
        });
    });

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) { // Verifica que el clic haya sido en el fondo oscuro y no adentro de los inputs del formulario
                this.classList.remove('activo'); //si tocamos fuera del modal
            }
        });
    });

    // Control Dinámico Modal Nuevo Viaje (Fecha vs Días)
    //porque si selecionas q es diario aparecen las opciones de tildar los checkb
    //y si es solo eventual no muestra la eleccion de dias
    const selectTipoViaje = document.getElementById('select-tipo-viaje');
    const inputFecha = document.getElementById('input-fecha-viaje');
    const grupoDias = document.getElementById('grupo-dias');

    if (selectTipoViaje) {
        selectTipoViaje.addEventListener('change', function() {
            if (this.value === 'eventual') {
                inputFecha.disabled = false;
                grupoDias.style.display = 'none';
            } else {
                inputFecha.disabled = true;
                inputFecha.value = '';
                grupoDias.style.display = 'block';
            }
        });
    }
});