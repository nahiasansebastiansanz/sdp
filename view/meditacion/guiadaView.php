<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🎧 Meditaciones Guiadas</h1>
    <p class="subtitulo">Elige la meditación que mejor se adapte a tu momento</p>

    <!-- Filtro por categoría -->
    <div class="filtros">
        <span>Filtrar por categoría:</span>
        <button type="button" class="botones btn-filtro activo" data-cat="0">Todas</button>
        <?php foreach ($categorias as $item): ?>
            <?php $cat = $item['modelo']; ?>
            <button type="button" class="botones btn-filtro" data-cat="<?php echo $cat->getIdCategoria(); ?>">
                <?php echo htmlspecialchars($cat->getIcono() . ' ' . $cat->getNombre()); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Grid de meditaciones -->
    <?php if (empty($meditaciones)): ?>
        <p class="texto-vacio">Aún no hay meditaciones disponibles.</p>
    <?php else: ?>
    <div class="tarjetas-grid" id="grid-meditaciones">
        <?php foreach ($meditaciones as $med): ?>
        <div class="tarjeta-med" data-cat="<?php echo $med->getIdCategoria(); ?>">
            <div class="tarjeta-cabecera">
                <span><?php echo htmlspecialchars($med->getIcono() . ' ' . $med->getTitulo()); ?></span>
                <span class="nivel nivel-<?php echo $med->getNivel(); ?>"><?php echo ucfirst($med->getNivel()); ?></span>
            </div>
            <div class="tarjeta-cuerpo">
                <p><?php echo htmlspecialchars($med->getDescripcion() ?? ''); ?></p>
                <p class="tarjeta-meta">
                    ⏱ <?php echo $med->getDuracionMin(); ?> min &nbsp;·&nbsp;
                    🏷 <?php echo htmlspecialchars($med->getNombreCategoria() ?? '-'); ?>
                </p>
                <div class="tarjeta-acciones">
                    <!-- Reproducir: registra una sesión guiada para los retos/logros -->
                    <form method="POST" action="/<?php echo $projectName; ?>/controller/SesionController.php" style="display:inline;">
                        <input type="hidden" name="action" value="registrar">
                        <input type="hidden" name="tipo" value="guiada">
                        <input type="hidden" name="duracion_min" value="<?php echo $med->getDuracionMin(); ?>">
                        <input type="hidden" name="id_meditacion" value="<?php echo $med->getIdMeditacion(); ?>">
                        <button type="submit" class="botones botones-primario btn-sm">▶ Reproducir</button>
                    </form>

                    <!-- Toggle favorito -->
                    <form method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php" style="display:inline;">
                        <input type="hidden" name="action" value="toggleFavorito">
                        <input type="hidden" name="id_meditacion" value="<?php echo $med->getIdMeditacion(); ?>">
                        <?php if (in_array($med->getIdMeditacion(), $ids_fav)): ?>
                            <input type="hidden" name="accion_fav" value="quitar">
                            <button type="submit" class="botones btn-sm btn-fav activo">❤️</button>
                        <?php else: ?>
                            <input type="hidden" name="accion_fav" value="añadir">
                            <button type="submit" class="botones btn-sm btn-fav">🤍</button>
                        <?php endif; ?>
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

<script>
document.querySelectorAll('.btn-filtro').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-filtro').forEach(b => b.classList.remove('activo'));
        this.classList.add('activo');
        var cat = this.dataset.cat;
        document.querySelectorAll('.tarjeta-med').forEach(function(card) {
            if (cat == '0' || card.dataset.cat == cat) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
