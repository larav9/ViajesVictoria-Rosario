<?= $this->include('templates/header') ?>

<div class="gestion-top">
    <h1>Panel de Control</h1>
</div>

<!-- menu -->
<div class="gestion-tabs">
    <button class="tab-btn activo" data-target="tab-viajes">Mis Viajes</button>
    <button class="tab-btn" data-target="tab-vehiculos">Mis Vehículos</button>
    <button class="tab-btn" data-target="tab-zonas">Mis Zonas</button>
</div>

<!-- ============================================== -->
<!-- seccion viajes -->
<div id="tab-viajes" class="tab-content activo">
    <div class="tab-header">
        <h2>Viajes Activos</h2>
        <button type="button" class="btn-accion btn-abrir-modal" data-modal="modal-nuevo-viaje">+ Publicar Viaje</button>
    </div>

    <table class="tabla-minimalista">
        <thead>
            <tr>
                <th>Ruta</th>
                <th>Tipo</th>
                <th>Fecha/Hora</th>
                <th>Capacidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($viajes as $v): ?>
            <tr>
                <td><strong><?= esc($v['origen']) ?></strong> → <strong><?= esc($v['destino']) ?></strong></td>
                <td><span class="badge badge-<?= $v['tipo'] ?>"><?= esc(ucfirst($v['tipo'])) ?></span></td>
                <td><?= empty($v['fecha']) ? 'Fijo' : date('d/m/Y', strtotime($v['fecha'])) ?> - <?= esc($v['hora_salida']) ?>hs</td>
                <td><?= esc($v['capacidad_pasajeros']) ?> pasajeros</td>
                <td>$<?= esc($v['precio_base']) ?></td>
                <td>
                    <button type="button" class="btn-icon btn-edit btn-edit-viaje" 
                            data-id="<?= $v['id'] ?>"
                            data-vehiculo="<?= $v['id_vehiculo'] ?>"
                            data-origen="<?= esc($v['origen']) ?>"
                            data-destino="<?= esc($v['destino']) ?>"
                            data-tipo="<?= esc($v['tipo']) ?>"
                            data-fecha="<?= esc($v['fecha'] ?? '') ?>"
                            data-hora="<?= esc($v['hora_salida']) ?>"
                            data-capacidad="<?= esc($v['capacidad_pasajeros']) ?>"
                            data-precio="<?= esc($v['precio_base']) ?>"
                            data-obs="<?= esc($v['observaciones'] ?? '') ?>"
                            data-dias='<?= esc(json_encode(array_column($v['dias'] ?? [], 'dia_semana')), 'attr') ?>'
                            data-zonas='<?= esc(json_encode($v['zonas'] ?? []), 'attr') ?>'>
                        ✎
                    </button>
                    <a href="<?= base_url('viajes/eliminar/'.$v['id']) ?>" class="btn-icon btn-delete" onclick="return confirm('¿Estás seguro de dar de baja este viaje?')">✖</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ============================================== -->
<!-- seccion vehiculos-->
<div id="tab-vehiculos" class="tab-content">
    <div class="tab-header">
        <h2>Mis Vehículos</h2>
        <button type="button" class="btn-accion btn-abrir-modal" data-modal="modal-nuevo-vehiculo">+ Agregar Vehículo</button>
    </div>

    <table class="tabla-minimalista">
        <thead>
            <tr>
                <th>Vehículo</th>
                <th>Año</th>
                <th>Color</th>
                <th>Capacidad</th>
                <th>Seguro</th>
                <th>Aseguradora</th>
                <th>Vencimiento</th>
                <th class="tabla-col-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($vehiculos as $veh): ?>
            <?php
                $tieneSeguro = !empty($veh['tiene_seguro']) && (string)$veh['tiene_seguro'] === '1';
                $vencimientoValido = !empty($veh['vencimiento_seguro']) && $veh['vencimiento_seguro'] !== '0000-00-00';
            ?>
            <tr>
                <td><strong><?= esc($veh['marca']) ?> <?= esc($veh['modelo']) ?></strong></td>
                <td><?= esc($veh['anio']) ?></td>
                <td><?= !empty($veh['color']) ? esc(ucfirst($veh['color'])) : '-' ?></td>
                <td><?= esc($veh['capacidad_pasajeros']) ?> lugares</td>
                <td>
                    <?php if ($tieneSeguro): ?>
                        <span class="badge badge-diario">Sí</span>
                    <?php else: ?>
                        <span class="badge badge-eventual">No</span>
                    <?php endif; ?>
                </td>
                <td><?= ($tieneSeguro && !empty($veh['aseguradora'])) ? esc($veh['aseguradora']) : '-' ?></td>
                <td><?= ($tieneSeguro && $vencimientoValido) ? date('d/m/Y', strtotime($veh['vencimiento_seguro'])) : '-' ?></td>
                <td>
                    <button type="button" class="btn-icon btn-edit btn-edit-vehiculo" 
                            data-id="<?= $veh['id'] ?>" 
                            data-marca="<?= esc($veh['marca']) ?>" 
                            data-modelo="<?= esc($veh['modelo']) ?>"
                            data-anio="<?= esc($veh['anio']) ?>"
                            data-color="<?= esc($veh['color'] ?? '') ?>"
                            data-pasajeros="<?= esc($veh['capacidad_pasajeros']) ?>"
                            data-seguro="<?= $tieneSeguro ? '1' : '0' ?>"
                            data-aseguradora="<?= esc($veh['aseguradora'] ?? '') ?>"
                            data-vencimiento="<?= $vencimientoValido ? esc($veh['vencimiento_seguro']) : '' ?>">
                        ✎
                    </button>
                    <a href="<?= base_url('vehiculos/eliminar/'.$veh['id']) ?>" class="btn-icon btn-delete" onclick="return confirm('¿Eliminar auto?')">✖</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ============================================== -->
<!-- seccion zonas -->
<div id="tab-zonas" class="tab-content">
    <div class="tab-header">
        <h2>Zonas de Recorrido</h2>
        <button type="button" class="btn-accion btn-abrir-modal" data-modal="modal-nueva-zona">+ Agregar Zona</button>
    </div>

    <table class="tabla-minimalista">
        <thead>
            <tr>
                <th>Nombre de la Zona</th>
                <th class="tabla-col-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($zonas as $z): ?>
            <tr>
                <td><strong><?= esc($z['nombre']) ?></strong></td>
                <td>
                    <button type="button" class="btn-icon btn-edit btn-edit-zona" 
                            data-id="<?= $z['id'] ?>" 
                            data-nombre="<?= esc($z['nombre']) ?>">
                        ✎
                    </button>
                    <a href="<?= base_url('zonas/eliminar/'.$z['id']) ?>" class="btn-icon btn-delete" onclick="return confirm('¿Eliminar zona?')">✖</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ============================================== -->
<!-- modales de alta -->
<!-- Modal Nuevo Viaje -->
<div id="modal-nuevo-viaje" class="modal-overlay">
    <div class="modal-content modal-grande">
        <div class="modal-header">
            <h3>Publicar Nuevo Viaje</h3>
            <button type="button" class="btn-cerrar-modal" data-modal="modal-nuevo-viaje">&times;</button>
        </div>
        <form action="<?= base_url('viajes/insertar') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="modal-fila">
                <div class="campo-grupo modal-col">
                    <label>Origen</label>
                    <input type="text" name="origen" required>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Destino</label>
                    <input type="text" name="destino" required>
                </div>
            </div>

            <div class="modal-fila">
                <div class="campo-grupo modal-col">
                    <label>Vehículo</label>
                    <select name="id_vehiculo" required>
                        <?php foreach ($vehiculos as $v): ?>
                            <option value="<?= $v['id'] ?>"><?= esc($v['marca'] . ' ' . $v['modelo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Tipo de Viaje</label>
                    <select name="tipo" id="select-tipo-viaje" required>
                        <option value="diario">Diario / Semanal</option>
                        <option value="eventual">Eventual (Única vez)</option>
                    </select>
                </div>
            </div>

            <div class="modal-fila">
                <div class="campo-grupo modal-col">
                    <label>Fecha (Solo eventual)</label>
                    <input type="date" name="fecha" id="input-fecha-viaje" disabled>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Hora de Salida</label>
                    <input type="time" name="hora_salida" required>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Capacidad de Pasajeros</label>
                    <input type="number" name="capacidad_pasajeros" required min="1">
                </div>
                <div class="campo-grupo modal-col">
                    <label>Precio Base ($)</label>
                    <input type="number" name="precio_base" required min="0">
                </div>
            </div>

            <div class="modal-fila-doble">
                <div class="campo-grupo modal-col" id="grupo-dias">
                    <label>Días de la semana</label>
                    <div class="contenedor-caja">
                        <?php $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; ?>
                        <?php foreach($dias as $dia): ?>
                            <label class="item-checkbox">
                                <input type="checkbox" name="dias[]" value="<?= $dia ?>"> 
                                <span><?= $dia ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if(!empty($zonas)): ?>
                <div class="campo-grupo modal-col">
                    <label>Zonas y Recargos (Opcional)</label>
                    <div class="contenedor-caja contenedor-caja-scroll">
                        <?php foreach($zonas as $z): ?>
                            <div class="item-zona-recargo">
                                <label class="item-checkbox">
                                    <input type="checkbox" name="zonas_seleccionadas[]" value="<?= $z['id'] ?>"> 
                                    <span><?= esc($z['nombre']) ?></span>
                                </label>
                                <div class="caja-recargo">
                                    <span class="simbolo-peso">$</span>
                                    <input type="number" name="recargo_<?= $z['id'] ?>" placeholder="0" min="0">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="campo-grupo">
                <label>Observaciones</label>
                <input type="text" name="observaciones" placeholder="Ej: Salgo desde la terminal...">
            </div>

            <button type="submit" class="btn-accion modal-btn-full">Guardar Viaje</button>
        </form>
    </div>
</div>

<!-- Modal Nuevo Vehículo -->
<div id="modal-nuevo-vehiculo" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Vehículo</h3>
            <button type="button" class="btn-cerrar-modal" data-modal="modal-nuevo-vehiculo">&times;</button>
        </div>
        <form action="<?= base_url('vehiculos/insertar') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-fila">
                <div class="campo-grupo modal-col"><label>Marca</label><input type="text" name="marca" required></div>
                <div class="campo-grupo modal-col"><label>Modelo</label><input type="text" name="modelo" required></div>
            </div>
            <div class="modal-fila">
                <div class="campo-grupo modal-col"><label>Año</label><input type="number" name="anio" required></div>
                <div class="campo-grupo modal-col"><label>Color</label><input type="text" name="color"></div>
                <div class="campo-grupo modal-col"><label>Pasajeros</label><input type="number" name="capacidad_pasajeros" required min="1"></div>
            </div>

            <div class="modal-fila">
                <div class="campo-grupo modal-col-seguro">
                    <label>¿Seguro?</label>
                    <select name="tiene_seguro" id="nuevo-veh-seguro" required>
                        <option value="1" selected>Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Aseguradora</label>
                    <input type="text" name="aseguradora" id="nuevo-veh-aseguradora" placeholder="Ej: La Segunda">
                </div>
                <div class="campo-grupo modal-col">
                    <label>Venc. Seguro</label>
                    <input type="date" name="vencimiento_seguro" id="nuevo-veh-vencimiento">
                </div>
            </div>

            <button type="submit" class="btn-accion modal-btn-full">Guardar Vehículo</button>
        </form>
    </div>
</div>

<!-- Modal Nueva Zona -->
<div id="modal-nueva-zona" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Zona</h3>
            <button type="button" class="btn-cerrar-modal" data-modal="modal-nueva-zona">&times;</button>
        </div>
        <form action="<?= base_url('zonas/insertar') ?>" method="post">
            <?= csrf_field() ?>
            <div class="campo-grupo">
                <label>Nombre de la Zona</label>
                <input type="text" name="nombre" required placeholder="Ej: Zona Sur / Terminal">
            </div>
            <button type="submit" class="btn-accion modal-btn-full">Guardar Zona</button>
        </form>
    </div>
</div>


<!-- ============================================== -->
<!-- modales ocultos para la edicion-->
<!-- Modal Editar Viaje -->
<div id="modal-viaje" class="modal-overlay">
    <div class="modal-content modal-grande">
        <div class="modal-header">
            <h3>Editar Viaje</h3>
            <button type="button" class="btn-cerrar-modal" data-modal="modal-viaje">&times;</button>
        </div>
        <form action="<?= base_url('viajes/actualizar') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="mod-viaje-id">
            
            <div class="modal-fila">
                <div class="campo-grupo modal-col">
                    <label>Origen</label>
                    <input type="text" name="origen" id="mod-viaje-origen" required>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Destino</label>
                    <input type="text" name="destino" id="mod-viaje-destino" required>
                </div>
            </div>

            <div class="modal-fila">
                <div class="campo-grupo modal-col">
                    <label>Vehículo</label>
                    <select name="id_vehiculo" id="mod-viaje-vehiculo" required>
                        <?php foreach ($vehiculos as $v): ?>
                            <option value="<?= $v['id'] ?>"><?= esc($v['marca'] . ' ' . $v['modelo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Tipo de Viaje</label>
                    <select name="tipo" id="mod-select-tipo-viaje" required>
                        <option value="diario">Diario / Semanal</option>
                        <option value="eventual">Eventual (Única vez)</option>
                    </select>
                </div>
            </div>

            <div class="modal-fila">
                <div class="campo-grupo modal-col">
                    <label>Fecha (Solo eventual)</label>
                    <input type="date" name="fecha" id="mod-input-fecha-viaje">
                </div>
                <div class="campo-grupo modal-col">
                    <label>Hora de Salida</label>
                    <input type="time" name="hora_salida" id="mod-viaje-hora" required>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Capacidad de Pasajeros</label>
                    <input type="number" name="capacidad_pasajeros" id="mod-viaje-capacidad" required min="1">
                </div>
                <div class="campo-grupo modal-col">
                    <label>Precio Base ($)</label>
                    <input type="number" name="precio_base" id="mod-viaje-precio" required min="0">
                </div>
            </div>

            <div class="modal-fila-doble">
                <div class="campo-grupo modal-col" id="mod-grupo-dias">
                    <label>Días de la semana</label>
                    <div class="contenedor-caja">
                        <?php $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; ?>
                        <?php foreach($dias as $dia): ?>
                            <label class="item-checkbox">
                                <input type="checkbox" name="dias[]" value="<?= $dia ?>" class="mod-chk-dia"> 
                                <span><?= $dia ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if(!empty($zonas)): ?>
                <div class="campo-grupo modal-col">
                    <label>Zonas y Recargos (Opcional)</label>
                    <div class="contenedor-caja contenedor-caja-scroll">
                        <?php foreach($zonas as $z): ?>
                            <div class="item-zona-recargo">
                                <label class="item-checkbox">
                                    <input type="checkbox" name="zonas_seleccionadas[]" value="<?= $z['id'] ?>" class="mod-chk-zona"> 
                                    <span><?= esc($z['nombre']) ?></span>
                                </label>
                                <div class="caja-recargo">
                                    <span class="simbolo-peso">$</span>
                                    <input type="number" name="recargo_<?= $z['id'] ?>" id="mod-recargo-<?= $z['id'] ?>" placeholder="0" min="0">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="campo-grupo">
                <label>Observaciones</label>
                <input type="text" name="observaciones" id="mod-viaje-obs">
            </div>

            <button type="submit" class="btn-accion modal-btn-full">Guardar Cambios</button>
        </form>
    </div>
</div>

<!-- Modal Editar Zona -->
<div id="modal-zona" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Zona</h3>
            <button type="button" class="btn-cerrar-modal" data-modal="modal-zona">&times;</button>
        </div>
        <form action="<?= base_url('zonas/actualizar') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="mod-zona-id">
            <div class="campo-grupo">
                <label>Nombre de la Zona</label>
                <input type="text" name="nombre" id="mod-zona-nombre" required>
            </div>
            <button type="submit" class="btn-accion modal-btn-full">Guardar Cambios</button>
        </form>
    </div>
</div>

<!-- Modal Editar Vehículo -->
<div id="modal-vehiculo" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Vehículo</h3>
            <button type="button" class="btn-cerrar-modal" data-modal="modal-vehiculo">&times;</button>
        </div>
        <form action="<?= base_url('vehiculos/actualizar') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="mod-veh-id">
            <div class="modal-fila">
                <div class="campo-grupo modal-col"><label>Marca</label><input type="text" name="marca" id="mod-veh-marca" required></div>
                <div class="campo-grupo modal-col"><label>Modelo</label><input type="text" name="modelo" id="mod-veh-modelo" required></div>
            </div>
            <div class="modal-fila">
                <div class="campo-grupo modal-col"><label>Año</label><input type="number" name="anio" id="mod-veh-anio" required></div>
                <div class="campo-grupo modal-col"><label>Color</label><input type="text" name="color" id="mod-veh-color"></div>
                <div class="campo-grupo modal-col"><label>Pasajeros</label><input type="number" name="capacidad_pasajeros" id="mod-veh-pasajeros" required min="1"></div>
            </div>
            
            <div class="modal-fila">
                <div class="campo-grupo modal-col-seguro">
                    <label>¿Seguro?</label>
                    <select name="tiene_seguro" id="mod-veh-seguro" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="campo-grupo modal-col">
                    <label>Aseguradora</label>
                    <input type="text" name="aseguradora" id="mod-veh-aseguradora" placeholder="Ej: La Segunda">
                </div>
                <div class="campo-grupo modal-col">
                    <label>Venc. Seguro</label>
                    <input type="date" name="vencimiento_seguro" id="mod-veh-vencimiento">
                </div>
            </div>

            <button type="submit" class="btn-accion modal-btn-full">Guardar Cambios</button>
        </form>
    </div>
</div>

<?= $this->include('templates/footer') ?>