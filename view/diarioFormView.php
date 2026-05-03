<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1><?php echo $entrada ? '✏️ Editar entrada' : '📝 Nueva entrada del diario'; ?></h1>
    <p class="subtitulo">Escribe cómo te has sentido hoy</p>

    <form id="diarioForm" method="POST" action="../controller/DiarioController.php">
        <input type="hidden" name="action" value="<?php echo $entrada ? 'actualizar' : 'guardar'; ?>">
        <?php if ($entrada): ?>
            <input type="hidden" name="id_entrada" value="<?php echo $entrada->getIdEntrada(); ?>">
        <?php endif; ?>

        <div class="formularios">
            <label for="titulo">Título: *</label>
            <input type="text" id="titulo" name="titulo" required
                   value="<?php echo htmlspecialchars($entrada ? $entrada->getTitulo() : ''); ?>">

            <label>¿Cómo te sientes?</label>
            <div class="grupo-radio">
                <label><input type="radio" name="humor" value="bien"
                    <?php echo (!$entrada || $entrada->getHumor() == 'bien') ? 'checked' : ''; ?>> 😊 Bien</label>
                <label><input type="radio" name="humor" value="neutral"
                    <?php echo ($entrada && $entrada->getHumor() == 'neutral') ? 'checked' : ''; ?>> 😐 Neutral</label>
                <label><input type="radio" name="humor" value="mal"
                    <?php echo ($entrada && $entrada->getHumor() == 'mal') ? 'checked' : ''; ?>> 😔 Mal</label>
            </div>

            <label for="contenido">Contenido: *</label>
            <textarea id="contenido" name="contenido" rows="7" required
                      style="resize:vertical;"><?php echo htmlspecialchars($entrada ? $entrada->getContenido() : ''); ?></textarea>
        </div>
    </form>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">Cancelar</button>
        <button type="submit" form="diarioForm" class="botones botones-primario">
            <?php echo $entrada ? '💾 Actualizar' : '💾 Guardar entrada'; ?>
        </button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
