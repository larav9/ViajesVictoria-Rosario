<?= $this->include('templates/header') ?>

<div class="cartelera-header" style="margin-bottom: 30px;">
    <h1>Cartelera de Viajes</h1>
    <p style="color: var(--texto-secundario);">Encontrá tu próximo viaje y contactá al chofer directamente.</p>
</div>

<div class="cartelera-grid">
    <?php if (empty($viajes)): ?>
        <p>No hay viajes disponibles en este momento.</p>
    <?php else: ?>
        <?php foreach ($viajes as $v): ?>
            <div class="trip-card">
                
                <div class="trip-origen-destino">
                    <h3><?= esc(empty($v['origen']) ? 'Victoria' : $v['origen']) ?></h3>
                    <span class="flecha">↓</span>
                    <h3><?= esc(empty($v['destino']) ? 'Rosario' : $v['destino']) ?></h3>
                </div>

                <div class="trip-datos">
                    <p><span>Chofer:</span> <strong><?= esc($v['chofer_nombre'] . ' ' . $v['chofer_apellido']) ?></strong></p>
                    <p><span>Fecha:</span> <strong><?= empty($v['fecha']) ? 'Fijo / Diario' : date('d/m/Y', strtotime($v['fecha'])) ?></strong></p>
                    <p><span>Salida:</span> <strong><?= substr(esc($v['hora_salida']), 0, 5) ?> hs</strong></p>
                    <p><span>Capacidad de pasajeros:</span> <strong><?= esc($v['capacidad_pasajeros']) ?> pasajeros</strong></p>
                    
                    <?php if (!empty($v['dias'])): ?>
                        <p><span>Días:</span> <strong><?php foreach($v['dias'] as $d) echo esc($d['dia_semana']) . " "; ?></strong></p>
                    <?php endif; ?>

                    <?php if (!empty($v['zonas'])): ?>
                        <div style="margin-top: 10px; font-size: 13px; background: var(--bg-pagina); padding: 10px; border-radius: 8px;">
                            <strong style="color: var(--lila-principal); display: block; margin-bottom: 6px;">Pasa por:</strong>
                            <?php foreach ($v['zonas'] as $z): ?>
                                <span style="display: block; margin-bottom: 4px;">• <?= esc($z['nombre']) ?> (+ $<?= esc($z['recargo']) ?>)</span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($v['observaciones'])): ?>
                        <p style="flex-direction: column; border:none; margin-top:12px;">
                            <span>Nota:</span>
                            <strong style="font-size:12px; margin-top:4px;"><?= esc($v['observaciones']) ?></strong>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="trip-precio">
                    $<?= number_format($v['precio_base'], 0, ',', '.') ?>
                </div>
                    <!-- si no es chofer -->
                <?php if (session()->get('logueado')): ?> 
                    <?php if (session()->get('rol') !== 'chofer'): ?>
                        <div class="trip-action">
                            <?php 
                                $hora = substr($v['hora_salida'], 0, 5);
                                $mensaje = "Hola " . esc($v['chofer_nombre']) . ", vi tu viaje en la cartelera de " . ($v['origen'] ?: 'Victoria') . " a " . ($v['destino'] ?: 'Rosario') . " a las {$hora}hs. ¿Tenés lugar?";
                                $urlWsp = "https://wa.me/" . esc($v['telefono_whatsapp']) . "?text=" . urlencode($mensaje);
                                //armamos la url de wsp concatenando el num y el msj 
                            ?>
                            <button type="button" 
                                    class="btn-wsp btn-consultar-ajax" 
                                    data-id-viaje="<?= $v['id'] ?>" 
                                    data-fecha="<?= esc($v['fecha'] ?? '') ?>"
                                    data-wsp="<?= $urlWsp ?>">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                </svg>
                                Consultar
                            </button>
                            <!-- Usamos <button> con data-wsp en vez de <a> para que ajax guarde la consulta en la bbdd antes de abrir wsp-->
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="trip-action">
                        <a href="<?= base_url('login') ?>" class="btn-wsp" style="background: var(--lila-principal); color: #ffffff;">
                            Iniciá sesión para contactar
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="config-consultas" 
     data-url="<?= base_url('consultas/guardar') ?>" 
     data-token-name="<?= csrf_token() ?>" 
     data-token-hash="<?= csrf_hash() ?>" 
     hidden></div>  <!-- div oculto con la url y el token que lee consultas.js al hacer clic en Consultar -->
<?= $this->include('templates/footer') ?>