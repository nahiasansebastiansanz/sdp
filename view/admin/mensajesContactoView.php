<?php include __DIR__ . '/../statics/cabeceraView.php'; ?>

<main>
    <h1>✉️ Mensajes de contacto</h1>
    <p class="subtitulo">Consulta y filtra los mensajes enviados desde el formulario de contacto.</p>

    <!-- Filtros -->
    <div class="filtros-card">
        <form method="GET" action="/<?php echo $projectName; ?>/controller/UsuarioController.php">
            <input type="hidden" name="action" value="listarMensajesContacto">
            <div class="filtros-grid">
                <div class="filtro-campo">
                    <label for="filtro_asunto">Asunto</label>
                    <select id="filtro_asunto" name="filtro_asunto">
                        <option value="">Todos</option>
                        <option value="soporte"     <?php echo (isset($_GET['filtro_asunto']) && $_GET['filtro_asunto'] === 'soporte')     ? 'selected' : ''; ?>>Soporte</option>
                        <option value="sugerencia"  <?php echo (isset($_GET['filtro_asunto']) && $_GET['filtro_asunto'] === 'sugerencia')  ? 'selected' : ''; ?>>Sugerencia</option>
                        <option value="colaboracion"<?php echo (isset($_GET['filtro_asunto']) && $_GET['filtro_asunto'] === 'colaboracion') ? 'selected' : ''; ?>>Colaboración</option>
                        <option value="otro"        <?php echo (isset($_GET['filtro_asunto']) && $_GET['filtro_asunto'] === 'otro')        ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                <div class="filtro-campo">
                    <label for="filtro_usuario">Usuario</label>
                    <select id="filtro_usuario" name="filtro_usuario">
                        <option value="">Todos</option>
                        <?php foreach ($usuarios as $u): ?>
                        <option value="<?php echo $u->getIdUsuario(); ?>" <?php echo (isset($_GET['filtro_usuario']) && (string)$_GET['filtro_usuario'] === (string)$u->getIdUsuario()) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($u->getNombreUsuario()); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filtro-campo">
                    <label for="filtro_desde">Desde</label>
                    <input type="date" id="filtro_desde" name="filtro_desde" value="<?php echo htmlspecialchars($_GET['filtro_desde'] ?? ''); ?>">
                </div>
                <div class="filtro-campo">
                    <label for="filtro_hasta">Hasta</label>
                    <input type="date" id="filtro_hasta" name="filtro_hasta" value="<?php echo htmlspecialchars($_GET['filtro_hasta'] ?? ''); ?>">
                </div>
            </div>
            <div class="filtros-acciones">
                <button type="submit" class="botones botones-primario">🔍 Filtrar</button>
                <a href="/<?php echo $projectName; ?>/controller/UsuarioController.php?action=listarMensajesContacto" class="botones">Limpiar</a>
            </div>
        </form>
    </div>

    <?php if (empty($mensajes)): ?>
        <p class="texto-vacio">No hay mensajes de contacto<?php echo (isset($_GET['filtro_asunto']) || isset($_GET['filtro_usuario']) || isset($_GET['filtro_desde']) || isset($_GET['filtro_hasta'])) ? ' con los filtros aplicados.' : '.'; ?></p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $m): ?>
                <tr>
                    <td><?php echo $m->getIdMensaje(); ?></td>
                    <td><?php echo htmlspecialchars($m->getFechaEnvio()); ?></td>
                    <td><?php echo htmlspecialchars($m->getNombre()); ?></td>
                    <td><?php echo $m->getIdUsuario() ? htmlspecialchars($m->getNombreUsuario() ?? '-') : '<em>Anónimo</em>'; ?></td>
                    <td><?php echo htmlspecialchars($m->getEmail()); ?></td>
                    <td><span class="badge"><?php echo htmlspecialchars($m->getAsunto()); ?></span></td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?php echo htmlspecialchars($m->getMensaje()); ?>"><?php $txt = $m->getMensaje(); echo htmlspecialchars(mb_strlen($txt) > 50 ? mb_substr($txt, 0, 50) . '…' : $txt); ?></td>
                    <td>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/UsuarioController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar este mensaje?');">
                            <input type="hidden" name="action" value="eliminarMensajeContacto">
                            <input type="hidden" name="id_mensaje" value="<?php echo $m->getIdMensaje(); ?>">
                            <button type="submit" class="botones botones-peligro btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="dashboardActions" style="margin-top:1rem;">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<?php include __DIR__ . '/../statics/pieView.php'; ?>
