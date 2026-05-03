<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🏆 Retos</h1>
    <p class="subtitulo">Acepta un reto y mantén la motivación</p>

    <div class="retos-layout">

        <div>
            <h2>Retos disponibles</h2>
            <?php if (empty($retosActivos)): ?>
                <p class="texto-vacio">No hay retos activos en este momento.</p>
            <?php else: ?>
            <?php foreach ($retosActivos as $reto): ?>
            <div class="tarjeta-reto">
                <div class="tarjeta-cabecera">
                    <span><?php echo htmlspecialchars($reto->getTitulo()); ?></span>
                    <span class="badge"><?php echo $reto->getDuracionDias(); ?> días</span>
                </div>
                <div class="tarjeta-cuerpo">
                    <p><?php echo htmlspecialchars($reto->getDescripcion() ?? ''); ?></p>
                    <p class="tarjeta-meta">Objetivo: <?php echo $reto->getObjetivoValor(); ?> <?php echo $reto->getTipo(); ?></p>
                    <?php if (!in_array($reto->getIdReto(), $ids_aceptados)): ?>
                        <form method="POST" action="../controller/RetoController.php">
                            <input type="hidden" name="action" value="aceptarReto">
                            <input type="hidden" name="id_reto" value="<?php echo $reto->getIdReto(); ?>">
                            <button type="submit" class="botones botones-primario">+ Aceptar reto</button>
                        </form>
                    <?php else: ?>
                        <span class="badge badge-ok">✅ Aceptado</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div>
            <h2>Mis retos en progreso</h2>
            <?php if (empty($retosUsuario)): ?>
                <p class="texto-vacio">Aún no has aceptado ningún reto.</p>
            <?php else: ?>
            <?php foreach ($retosUsuario as $ur): ?>
            <div class="tarjeta-reto">
                <strong><?php echo htmlspecialchars($ur['titulo']); ?></strong>
                <p class="tarjeta-meta">
                    Progreso: <?php echo $ur['progreso']; ?> / <?php echo $ur['objetivo_valor']; ?>
                    &nbsp;(<?php echo $ur['objetivo_valor'] > 0 ? round($ur['progreso'] * 100 / $ur['objetivo_valor']) : 0; ?>%)
                </p>
                <div class="barra-progreso">
                    <div class="barra-relleno" style="width:<?php
                        echo $ur['objetivo_valor'] > 0 ? min(100, round($ur['progreso'] * 100 / $ur['objetivo_valor'])) : 0;
                    ?>%"></div>
                </div>
                <?php if ($ur['completado']): ?>
                    <span class="badge badge-ok">🏆 Completado</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
