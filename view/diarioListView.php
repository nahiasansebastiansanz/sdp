<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>📝 Mi Diario</h1>
    <p class="subtitulo">Tus reflexiones tras cada sesión</p>

    <div class="dashboardActions" style="justify-content:flex-end;">
        <form method="POST" action="../controller/DiarioController.php">
            <input type="hidden" name="action" value="formularioNueva">
            <button type="submit" class="botones botones-primario">+ Nueva entrada</button>
        </form>
    </div>

    <?php if (empty($entradas)): ?>
        <p class="texto-vacio">Aún no has escrito ninguna entrada. ¡Escribe cómo te has sentido tras tu primera sesión!</p>
    <?php else: ?>
    <div class="tarjetas-grid">
        <?php foreach ($entradas as $entrada): ?>
        <div class="tarjeta-diario">
            <div class="tarjeta-cabecera">
                <span><?php echo htmlspecialchars($entrada->getTitulo()); ?></span>
                <span class="humor humor-<?php echo $entrada->getHumor(); ?>">
                    <?php
                    $humorTexto = '😐 Neutral';
                    switch ($entrada->getHumor()) {
                        case 'bien':
                            $humorTexto = '😊 Bien';
                            break;
                        case 'mal':
                            $humorTexto = '😔 Mal';
                            break;
                    }
                    echo $humorTexto;
                    ?>
                </span>
            </div>
            <div class="tarjeta-cuerpo">
                <p class="tarjeta-meta">📅 <?php echo $entrada->getFechaEntrada(); ?></p>
                <p><?php echo htmlspecialchars(mb_substr($entrada->getContenido(), 0, 120)) . '…'; ?></p>
                <div class="tarjeta-acciones">
                    <!-- Editar -->
                    <form method="POST" action="../controller/DiarioController.php" style="display:inline;">
                        <input type="hidden" name="action" value="formularioEditar">
                        <input type="hidden" name="id_entrada" value="<?php echo $entrada->getIdEntrada(); ?>">
                        <button type="submit" class="botones btn-sm">✏️ Editar</button>
                    </form>
                    <!-- Eliminar -->
                    <form method="POST" action="../controller/DiarioController.php" style="display:inline;"
                          onsubmit="return confirm('¿Eliminar esta entrada?');">
                        <input type="hidden" name="action" value="eliminar">
                        <input type="hidden" name="id_entrada" value="<?php echo $entrada->getIdEntrada(); ?>">
                        <button type="submit" class="botones botones-peligro btn-sm">🗑 Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
