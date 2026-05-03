<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🏅 Gestión de Logros</h1>

    <!-- Formulario inline -->
    <div class="panel-sesion">
        <h2 id="logro-form-titulo">Nuevo logro</h2>
        <form id="logroForm" method="POST" action="/<?php echo $projectName; ?>/controller/LogroController.php">
            <input type="hidden" name="action" id="logro-action" value="guardar">
            <input type="hidden" name="id_logro" id="logro-id" value="">
            <div class="formularios">
                <label for="logro-titulo-inp">Título: *</label>
                <input type="text" id="logro-titulo-inp" name="titulo" required>

                <label for="logro-icono">Icono (emoji):</label>
                <input type="text" id="logro-icono" name="icono" maxlength="10" placeholder="🏅">

                <label for="logro-desc">Descripción:</label>
                <input type="text" id="logro-desc" name="descripcion">

                <label for="logro-tipo">Condición: *</label>
                <select id="logro-tipo" name="condicion_tipo" required>
                    <option value="">Selecciona...</option>
                    <option value="sesiones">Número de sesiones</option>
                    <option value="minutos">Minutos acumulados</option>
                    <option value="racha">Días de racha</option>
                </select>

                <label for="logro-valor">Valor: *</label>
                <input type="number" id="logro-valor" name="condicion_valor" min="1" required>
            </div>
        </form>
        <div class="dashboardActions">
            <button type="button" class="botones" onclick="limpiarLogroForm()">Cancelar</button>
            <button type="submit" form="logroForm" class="botones botones-primario" id="logro-btn">➕ Crear logro</button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr><th>Icono</th><th>Título</th><th>Descripción</th><th>Condición</th><th>Valor</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($logros as $l): ?>
                <tr>
                    <td style="font-size:1.5rem;"><?php echo htmlspecialchars($l->getIcono()); ?></td>
                    <td><?php echo htmlspecialchars($l->getTitulo()); ?></td>
                    <td><?php echo htmlspecialchars($l->getDescripcion() ?? ''); ?></td>
                    <td><span class="badge"><?php echo $l->getCondicionTipo(); ?></span></td>
                    <td><?php echo $l->getCondicionValor(); ?></td>
                    <td>
                        <button type="button" class="botones btn-sm"
                                onclick="editarLogro(<?php echo $l->getIdLogro(); ?>, '<?php echo addslashes($l->getTitulo()); ?>', '<?php echo addslashes($l->getIcono()); ?>', '<?php echo addslashes($l->getDescripcion() ?? ''); ?>', '<?php echo $l->getCondicionTipo(); ?>', <?php echo $l->getCondicionValor(); ?>)">
                            ✏️ Editar
                        </button>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/LogroController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar este logro?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_logro" value="<?php echo $l->getIdLogro(); ?>">
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
function editarLogro(id, titulo, icono, desc, tipo, valor) {
    document.getElementById('logro-action').value    = 'actualizar';
    document.getElementById('logro-id').value        = id;
    document.getElementById('logro-titulo-inp').value= titulo;
    document.getElementById('logro-icono').value     = icono;
    document.getElementById('logro-desc').value      = desc;
    document.getElementById('logro-tipo').value      = tipo;
    document.getElementById('logro-valor').value     = valor;
    document.getElementById('logro-form-titulo').textContent = 'Editar logro';
    document.getElementById('logro-btn').textContent = '💾 Actualizar';
    window.scrollTo(0, 0);
}
function limpiarLogroForm() {
    document.getElementById('logro-action').value    = 'guardar';
    document.getElementById('logro-id').value        = '';
    document.getElementById('logro-titulo-inp').value= '';
    document.getElementById('logro-icono').value     = '';
    document.getElementById('logro-desc').value      = '';
    document.getElementById('logro-tipo').value      = '';
    document.getElementById('logro-valor').value     = '';
    document.getElementById('logro-form-titulo').textContent = 'Nuevo logro';
    document.getElementById('logro-btn').textContent = '➕ Crear logro';
}
</script>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
