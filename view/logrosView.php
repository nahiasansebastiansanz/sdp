<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🏅 Mis Logros</h1>
    <p class="subtitulo">Insignias desbloqueadas por tu constancia</p>

    <!-- Obtenidos -->
    <?php
    $logrosObtenidosIds = array_column($obtenidos, 'id_logro');
    $obtHTML = array_filter($todosLogros, fn($l) => in_array($l->getIdLogro(), $logrosObtenidosIds));
    $pendHTML = array_filter($todosLogros, fn($l) => !in_array($l->getIdLogro(), $logrosObtenidosIds));
    ?>

    <h2>✅ Obtenidos (<?php echo count($obtHTML); ?>)</h2>
    <?php if (empty($obtHTML)): ?>
        <p class="texto-vacio">Aún no has desbloqueado ningún logro. ¡Sigue meditando!</p>
    <?php else: ?>
    <div class="logros-grid">
        <?php foreach ($obtHTML as $logro):
            $fo = array_filter($obtenidos, fn($o) => $o['id_logro'] == $logro->getIdLogro());
            $fo = reset($fo);
        ?>
        <div class="tarjeta-logro obtenido">
            <div class="logro-icono"><?php echo htmlspecialchars($logro->getIcono()); ?></div>
            <div class="logro-titulo"><?php echo htmlspecialchars($logro->getTitulo()); ?></div>
            <div class="logro-desc"><?php echo htmlspecialchars($logro->getDescripcion() ?? ''); ?></div>
            <?php if ($fo): ?>
                <div class="logro-fecha">🗓 <?php echo $fo['fecha_obtencion']; ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h2 style="margin-top:2rem;">🔒 Por desbloquear (<?php echo count($pendHTML); ?>)</h2>
    <?php if (!empty($pendHTML)): ?>
    <div class="logros-grid">
        <?php foreach ($pendHTML as $logro): ?>
        <div class="tarjeta-logro bloqueado">
            <div class="logro-icono"><?php echo htmlspecialchars($logro->getIcono()); ?></div>
            <div class="logro-titulo"><?php echo htmlspecialchars($logro->getTitulo()); ?></div>
            <div class="logro-desc"><?php echo htmlspecialchars($logro->getDescripcion() ?? ''); ?></div>
            <div class="logro-cond">
                <?php echo $logro->getCondicionTipo(); ?>: <?php echo $logro->getCondicionValor(); ?>
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
