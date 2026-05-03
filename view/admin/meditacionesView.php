<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🧘 Gestión de Meditaciones</h1>

    <div class="dashboardActions" style="justify-content:flex-end;">
        <form method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php">
            <input type="hidden" name="action" value="formularioNueva">
            <button type="submit" class="botones botones-primario">+ Nueva meditación</button>
        </form>
    </div>

    <?php if (empty($meditaciones)): ?>
        <p class="texto-vacio">No hay meditaciones registradas.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr><th>#</th><th>Título</th><th>Categoría</th><th>Nivel</th><th>Duración</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($meditaciones as $m): ?>
                <tr>
                    <td><?php echo $m->getIdMeditacion(); ?></td>
                    <td><?php echo htmlspecialchars($m->getIcono() . ' ' . $m->getTitulo()); ?></td>
                    <td><span class="badge"><?php echo htmlspecialchars($m->getNombreCategoria() ?? '-'); ?></span></td>
                    <td><span class="nivel nivel-<?php echo $m->getNivel(); ?>"><?php echo ucfirst($m->getNivel()); ?></span></td>
                    <td><?php echo $m->getDuracionMin(); ?> min</td>
                    <td>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php" style="display:inline;">
                            <input type="hidden" name="action" value="formularioEditar">
                            <input type="hidden" name="id_meditacion" value="<?php echo $m->getIdMeditacion(); ?>">
                            <button type="submit" class="botones btn-sm">✏️</button>
                        </form>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar esta meditación?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_meditacion" value="<?php echo $m->getIdMeditacion(); ?>">
                            <button type="submit" class="botones botones-peligro btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
