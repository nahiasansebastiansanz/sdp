<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🏆 Gestión de Retos</h1>

    <!-- Formulario inline -->
    <div class="panel-sesion">
        <h2 id="reto-form-titulo">Nuevo reto</h2>
        <form id="retoForm" method="POST" action="/<?php echo $projectName; ?>/controller/RetoController.php">
            <input type="hidden" name="action" id="reto-action" value="guardar">
            <input type="hidden" name="id_reto" id="reto-id" value="">
            <div class="formularios">
                <label for="reto-titulo-inp">Título: *</label>
                <input type="text" id="reto-titulo-inp" name="titulo" required>

                <label for="reto-desc">Descripción:</label>
                <input type="text" id="reto-desc" name="descripcion">

                <label for="reto-tipo">Tipo: *</label>
                <select id="reto-tipo" name="tipo" required>
                    <option value="">Selecciona...</option>
                    <option value="racha">Racha (días)</option>
                    <option value="minutos">Minutos</option>
                    <option value="sesiones">Sesiones</option>
                </select>

                <label for="reto-obj">Objetivo: *</label>
                <input type="number" id="reto-obj" name="objetivo_valor" min="1" required>

                <label for="reto-dias">Duración (días): *</label>
                <input type="number" id="reto-dias" name="duracion_dias" min="1" required>

                <label for="reto-activo">Activo:</label>
                <input type="checkbox" id="reto-activo" name="activo" checked>
            </div>
        </form>
        <div class="dashboardActions">
            <button type="button" class="botones" onclick="limpiarRetoForm()">Cancelar</button>
            <button type="submit" form="retoForm" class="botones botones-primario" id="reto-btn">➕ Crear reto</button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr><th>Título</th><th>Tipo</th><th>Objetivo</th><th>Días</th><th>Activo</th><th>Participantes</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($retos as $item):
                    $r   = $item['modelo'];
                    $num = $item['num_participantes'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($r->getTitulo()); ?></td>
                    <td><span class="badge"><?php echo $r->getTipo(); ?></span></td>
                    <td><?php echo $r->getObjetivoValor(); ?></td>
                    <td><?php echo $r->getDuracionDias(); ?></td>
                    <td><?php echo $r->getActivo() ? '✅' : '❌'; ?></td>
                    <td><?php echo $num; ?></td>
                    <td>
                        <button type="button" class="botones btn-sm"
                                onclick="editarReto(<?php echo $r->getIdReto(); ?>, '<?php echo addslashes($r->getTitulo()); ?>', '<?php echo addslashes($r->getDescripcion() ?? ''); ?>', '<?php echo $r->getTipo(); ?>', <?php echo $r->getObjetivoValor(); ?>, <?php echo $r->getDuracionDias(); ?>, <?php echo $r->getActivo(); ?>)">
                            ✏️ Editar
                        </button>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/RetoController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar este reto?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_reto" value="<?php echo $r->getIdReto(); ?>">
                            <button type="submit" class="botones botones-peligro btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<script>
function editarReto(id, titulo, desc, tipo, obj, dias, activo) {
    document.getElementById('reto-action').value    = 'actualizar';
    document.getElementById('reto-id').value        = id;
    document.getElementById('reto-titulo-inp').value= titulo;
    document.getElementById('reto-desc').value      = desc;
    document.getElementById('reto-tipo').value      = tipo;
    document.getElementById('reto-obj').value       = obj;
    document.getElementById('reto-dias').value      = dias;
    document.getElementById('reto-activo').checked  = activo == 1;
    document.getElementById('reto-form-titulo').textContent = 'Editar reto';
    document.getElementById('reto-btn').textContent = '💾 Actualizar';
    window.scrollTo(0, 0);
}
function limpiarRetoForm() {
    document.getElementById('reto-action').value    = 'guardar';
    document.getElementById('reto-id').value        = '';
    document.getElementById('reto-titulo-inp').value= '';
    document.getElementById('reto-desc').value      = '';
    document.getElementById('reto-tipo').value      = '';
    document.getElementById('reto-obj').value       = '';
    document.getElementById('reto-dias').value      = '';
    document.getElementById('reto-activo').checked  = true;
    document.getElementById('reto-form-titulo').textContent = 'Nuevo reto';
    document.getElementById('reto-btn').textContent = '➕ Crear reto';
}
</script>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
