<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>¿Cómo quieres meditar hoy?</h1>
    <p class="subtitulo">Elige el tipo de práctica que mejor se adapte a tu momento</p>

    <div class="tarjetas-grid">

        <div class="tarjeta-tipo">
            <div class="tarjeta-icono">⏱️</div>
            <h2>Meditación libre</h2>
            <p>Configura tu temporizador y medita en silencio. Gong inteligente opcional.</p>
            <form method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php">
                <input type="hidden" name="action" value="libre">
                <button type="submit" class="botones botones-primario">Elegir</button>
            </form>
        </div>

        <div class="tarjeta-tipo">
            <div class="tarjeta-icono">🎧</div>
            <h2>Meditación guiada</h2>
            <p>Una voz te acompaña paso a paso. Ideal si estás empezando.</p>
            <form method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php">
                <input type="hidden" name="action" value="guiada">
                <button type="submit" class="botones botones-acento">Elegir</button>
            </form>
        </div>

        <div class="tarjeta-tipo">
            <div class="tarjeta-icono">🌬️</div>
            <h2>Respiración guiada</h2>
            <p>Técnicas de respiración para calmar la ansiedad al instante.</p>
            <form method="POST" action="/<?php echo $projectName; ?>/controller/RespiracionController.php">
                <input type="hidden" name="action" value="listar">
                <button type="submit" class="botones">Elegir</button>
            </form>
        </div>

    </div>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
