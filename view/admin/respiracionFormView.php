<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1><?php echo $respiracion ? '✏️ Editar técnica' : '🌬️ Nueva técnica de respiración'; ?></h1>

    <form id="respForm" method="POST" action="/<?php echo $projectName; ?>/controller/RespiracionController.php">
        <input type="hidden" name="action" value="<?php echo $respiracion ? 'actualizar' : 'guardar'; ?>">
        <?php if ($respiracion): ?>
            <input type="hidden" name="id_respiracion" value="<?php echo $respiracion->getIdRespiracion(); ?>">
        <?php endif; ?>

        <div class="formularios">
            <label for="nombre">Nombre: *</label>
            <input type="text" id="nombre" name="nombre" required
                   value="<?php echo htmlspecialchars($respiracion ? $respiracion->getNombre() : ''); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="2" style="resize:vertical;"
            ><?php echo htmlspecialchars($respiracion ? ($respiracion->getDescripcion() ?? '') : ''); ?></textarea>

            <label for="inhala_seg">↑ Inhala (seg): *</label>
            <input type="number" id="inhala_seg" name="inhala_seg" min="1" required
                   value="<?php echo $respiracion ? $respiracion->getInhalaSeg() : ''; ?>" oninput="calcDur()">

            <label for="retiene_seg">⏸ Retención 1 (seg):</label>
            <input type="number" id="retiene_seg" name="retiene_seg" min="0" value="<?php echo $respiracion ? $respiracion->getRetieneSeg() : 0; ?>" oninput="calcDur()">

            <label for="exhala_seg">↓ Exhala (seg): *</label>
            <input type="number" id="exhala_seg" name="exhala_seg" min="1" required
                   value="<?php echo $respiracion ? $respiracion->getExhalaSeg() : ''; ?>" oninput="calcDur()">

            <label for="retiene2_seg">⏸ Retención 2 (seg):</label>
            <input type="number" id="retiene2_seg" name="retiene2_seg" min="0" value="<?php echo $respiracion ? $respiracion->getRetiene2Seg() : 0; ?>" oninput="calcDur()">

            <label for="ciclos">Número de ciclos: *</label>
            <input type="number" id="ciclos" name="ciclos" min="1" required
                   value="<?php echo $respiracion ? $respiracion->getCiclos() : ''; ?>" oninput="calcDur()">

            <label>Duración estimada:</label>
            <span id="dur-est" class="texto-muted">-</span>
        </div>
    </form>

    <div class="dashboardActions">
        <a href="/<?php echo $projectName; ?>/controller/RespiracionController.php?action=listarAdmin" class="botones">Cancelar</a>
        <button type="submit" form="respForm" class="botones botones-primario">
            <?php echo $respiracion ? '💾 Actualizar' : '➕ Crear técnica'; ?>
        </button>
    </div>
</main>

<script>
function calcDur() {
    var i  = parseInt(document.getElementById('inhala_seg').value)   || 0;
    var r  = parseInt(document.getElementById('retiene_seg').value)  || 0;
    var e  = parseInt(document.getElementById('exhala_seg').value)   || 0;
    var r2 = parseInt(document.getElementById('retiene2_seg').value) || 0;
    var c  = parseInt(document.getElementById('ciclos').value)       || 0;
    var seg = (i + r + e + r2) * c;
    document.getElementById('dur-est').textContent = seg > 0
        ? (seg / 60).toFixed(1) + ' min (' + seg + ' seg)'
        : '-';
}
</script>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
