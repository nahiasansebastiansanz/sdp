<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🌬️ Gestión de Respiraciones</h1>

    <div class="dashboardActions" style="justify-content:flex-end;">
        <form method="POST" action="/<?php echo $projectName; ?>/controller/RespiracionController.php">
            <input type="hidden" name="action" value="formularioNueva">
            <button type="submit" class="botones botones-primario">+ Nueva técnica</button>
        </form>
    </div>

    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr><th>#</th><th>Nombre</th><th>Inhala</th><th>Retén</th><th>Exhala</th><th>Retén 2</th><th>Ciclos</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($respiraciones as $r): ?>
                <tr>
                    <td><?php echo $r->getIdRespiracion(); ?></td>
                    <td><?php echo htmlspecialchars($r->getNombre()); ?></td>
                    <td><?php echo $r->getInhalaSeg(); ?>s</td>
                    <td><?php echo $r->getRetieneSeg(); ?>s</td>
                    <td><?php echo $r->getExhalaSeg(); ?>s</td>
                    <td><?php echo $r->getRetiene2Seg(); ?>s</td>
                    <td><?php echo $r->getCiclos(); ?></td>
                    <td>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/RespiracionController.php" style="display:inline;">
                            <input type="hidden" name="action" value="formularioEditar">
                            <input type="hidden" name="id_respiracion" value="<?php echo $r->getIdRespiracion(); ?>">
                            <button type="submit" class="botones btn-sm">✏️</button>
                        </form>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/RespiracionController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar esta técnica?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_respiracion" value="<?php echo $r->getIdRespiracion(); ?>">
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

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
