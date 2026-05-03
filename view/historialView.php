<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🕐 Historial de Sesiones</h1>
    <p class="subtitulo">Todas tus sesiones de meditación registradas</p>

    <?php if (empty($sesiones)): ?>
        <p class="texto-vacio">Aún no tienes sesiones registradas.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Duración</th>
                    <th>Gong</th>
                    <th>Meditación</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sesiones as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['fecha_sesion']); ?></td>
                    <td>
                        <?php
                        $tipoTexto = htmlspecialchars($s['tipo']);
                        switch ($s['tipo']) {
                            case 'libre':
                                $tipoTexto = '⏱️ Libre';
                                break;
                            case 'guiada':
                                $tipoTexto = '🎧 Guiada';
                                break;
                            case 'respiracion':
                                $tipoTexto = '🌬️ Respiración';
                                break;
                        }
                        echo $tipoTexto;
                        ?>
                    </td>
                    <td><?php echo $s['duracion_min']; ?> min</td>
                    <td><?php echo $s['con_gong'] ? '🔔' : '-'; ?></td>
                    <td><?php echo htmlspecialchars($s['titulo_meditacion'] ?? '-'); ?></td>
                    <td>
                        <form method="POST" action="../controller/SesionController.php"
                              onsubmit="return confirm('¿Eliminar esta sesión del historial?');">
                            <input type="hidden" name="action" value="eliminarSesion">
                            <input type="hidden" name="id_sesion" value="<?php echo $s['id_sesion']; ?>">
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

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
