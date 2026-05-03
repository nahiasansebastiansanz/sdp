<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';
include __DIR__ . '/../view/statics/cabeceraView.php';
?>

<main>
    <h1>✉️ Contacto</h1>
    <p class="subtitulo">¿Tienes alguna pregunta o sugerencia? Escríbenos.</p>

    <?php if (isset($_SESSION['msg_contacto_ok'])): ?>
        <div class="alerta alerta-ok">
            ✅ Mensaje enviado. Te responderemos lo antes posible.
            <?php if (!empty($_SESSION['msg_contacto_ticket'])): ?>
                <br><small>Nº de incidencia: <strong><?php echo htmlspecialchars($_SESSION['msg_contacto_ticket']); ?></strong></small>
            <?php endif; ?>
        </div>
        <?php unset($_SESSION['msg_contacto_ok'], $_SESSION['msg_contacto_ticket']); ?>
    <?php endif; ?>

    <form id="contactoForm" method="POST" action="../controller/UsuarioController.php">
        <input type="hidden" name="action" value="contacto">

        <div class="formularios">
            <label for="c-nombre">Nombre: *</label>
            <input type="text" id="c-nombre" name="nombre" required
                   value="<?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? ''); ?>">

            <label for="c-email">Email: *</label>
            <input type="email" id="c-email" name="email" required
                   value="<?php
                       $emailPrefill = $_SESSION['email'] ?? '';
                       if ($emailPrefill === '' && !empty($_SESSION['id_usuario'])) {
                           $u = UsuarioDAO::obtenerPorId($_SESSION['id_usuario']);
                           if ($u) {
                               $emailPrefill = $u->getEmail();
                               $_SESSION['email'] = $emailPrefill;
                           }
                       }
                       echo htmlspecialchars($emailPrefill);
                   ?>">

            <label for="c-asunto">Asunto: *</label>
            <select id="c-asunto" name="asunto" required>
                <option value="">Selecciona...</option>
                <option value="soporte">Soporte técnico</option>
                <option value="sugerencia">Sugerencia</option>
                <option value="colaboracion">Colaboración</option>
                <option value="otro">Otro</option>
            </select>

            <label for="c-mensaje">Mensaje: *</label>
            <textarea id="c-mensaje" name="mensaje" rows="6" required
                      style="resize:vertical;" placeholder="Escribe tu mensaje aquí..."></textarea>
        </div>

        <div id="c-errores" style="color:var(--peligro); margin-top:.5rem; text-align:center;"></div>
    </form>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
        <button type="submit" form="contactoForm" class="botones botones-primario">📨 Enviar mensaje</button>
    </div>

    <!-- Info de contacto -->
    <div class="tarjetas-grid" style="margin-top:2rem;">
        <div class="tarjeta-tipo" style="cursor:default; padding:1.5rem;">
            <div class="tarjeta-icono" style="font-size:2rem;">📧</div>
            <h3>Email</h3>
            <p style="color:var(--texto-muted);">hola@sdp-meditacion.es</p>
        </div>
        <div class="tarjeta-tipo" style="cursor:default; padding:1.5rem;">
            <div class="tarjeta-icono" style="font-size:2rem;">🏫</div>
            <h3>Proyecto DAW</h3>
            <p style="color:var(--texto-muted);">Grupo 7 - Curso 2025-2026<br>Docente: Javier Martín Martín</p>
        </div>
        <div class="tarjeta-tipo" style="cursor:default; padding:1.5rem;">
            <div class="tarjeta-icono" style="font-size:2rem;">⏱️</div>
            <h3>Respuesta</h3>
            <p style="color:var(--texto-muted);">Respondemos en menos de 48 horas laborables.</p>
        </div>
    </div>
</main>


<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
