<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>❤️ Mis Favoritos</h1>
    <p class="subtitulo">Las meditaciones que más te gustan</p>

    <?php if (empty($favoritos)): ?>
        <p class="texto-vacio">Aún no has guardado ninguna meditación como favorita. Usa el botón 🤍 en las meditaciones guiadas.</p>
    <?php else: ?>
    <div class="tarjetas-grid">
        <?php foreach ($favoritos as $med): ?>
        <div class="tarjeta-med">
            <div class="tarjeta-cabecera">
                <span><?php echo htmlspecialchars($med->getIcono() . ' ' . $med->getTitulo()); ?></span>
                <span class="nivel nivel-<?php echo $med->getNivel(); ?>"><?php echo ucfirst($med->getNivel()); ?></span>
            </div>
            <div class="tarjeta-cuerpo">
                <p><?php echo htmlspecialchars($med->getDescripcion() ?? ''); ?></p>
                <p class="tarjeta-meta">⏱ <?php echo $med->getDuracionMin(); ?> min &nbsp;·&nbsp; 🏷 <?php echo htmlspecialchars($med->getNombreCategoria() ?? '-'); ?></p>
                <div class="tarjeta-acciones">
                    <button type="button" class="botones botones-primario btn-sm"
                            onclick="alert('🎧 Reproduciendo: <?php echo addslashes($med->getTitulo()); ?>')">▶ Reproducir</button>
                    <form method="POST" action="../controller/MeditacionController.php" style="display:inline;">
                        <input type="hidden" name="action" value="toggleFavorito">
                        <input type="hidden" name="id_meditacion" value="<?php echo $med->getIdMeditacion(); ?>">
                        <input type="hidden" name="accion_fav" value="quitar">
                        <button type="submit" class="botones botones-peligro btn-sm">❤️ Quitar</button>
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
