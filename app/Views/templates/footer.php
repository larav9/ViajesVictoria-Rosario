</main>
</div>

<?php if (session()->get('logueado')): ?>
<div id="modal-perfil" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Perfil</h3>
            <button type="button" class="btn-cerrar-modal" id="btn-cerrar-perfil">&times;</button>
        </div>
        <form action="<?= base_url('perfil/actualizar') ?>" method="post">
            <?= csrf_field() ?>

            <div class="campo-grupo">
                <label>Usuario</label>
                <input type="text" value="<?= esc(session()->get('usuario')) ?>" disabled>
            </div>

            <div class="campo-grupo">
                <label for="perfil-wsp">Número de WhatsApp (549 + área sin 15 + número)</label>
                <input type="text" name="telefono_whatsapp" id="perfil-wsp"
                       value="<?= esc(session()->get('telefono_whatsapp') ?? '') ?>"
                       placeholder="5493436123456" <?= (session()->get('rol') === 'chofer') ? 'required' : '' ?>>
            </div>

            <div class="campo-grupo">
                <label for="perfil-clave">Nueva Contraseña (opcional)</label>
                <div class="password-wrapper">
                    <input type="password" name="clave" id="perfil-clave"
                           placeholder="Mín. 8 caracteres, 1 mayúscula y 1 número">
                    <button type="button" class="btn-toggle-pass tachado" id="toggle-perfil-clave" aria-label="Mostrar contraseña" title="Mostrar/ocultar contraseña">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                            <line class="linea-tachado" x1="2" y1="2" x2="22" y2="22"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-ingresar">Guardar Cambios</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/js/perfil.js') ?>"></script>
<script src="<?= base_url('assets/js/consultas.js') ?>"></script>
<script src="<?= base_url('assets/js/gestion.js') ?>"></script>
</body>
</html>