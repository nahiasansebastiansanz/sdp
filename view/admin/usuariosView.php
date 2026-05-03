<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>👥 Gestión de Usuarios</h1>

    <div class="dashboardActions" style="justify-content:flex-end;">
        <form method="POST" action="/<?php echo $projectName; ?>/controller/UsuarioController.php">
            <input type="hidden" name="action" value="editarUsuario">
            <input type="hidden" name="id_usuario" value="0">
        </form>
        <button type="button" class="botones botones-primario"
                onclick="window.location.href='/<?php echo $projectName; ?>/view/admin/usuarioFormView.php?nuevo=1'">+ Nuevo usuario</button>
    </div>

    <?php if (empty($usuarios)): ?>
        <p class="texto-vacio">No hay usuarios registrados.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr>
                    <th>#</th><th>Usuario</th><th>Nombre</th><th>Email</th><th>Perfil</th><th>Fecha alta</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u->getIdUsuario(); ?></td>
                    <td><?php echo htmlspecialchars($u->getNombreUsuario()); ?></td>
                    <td><?php echo htmlspecialchars($u->getNombreCompleto()); ?></td>
                    <td><?php echo htmlspecialchars($u->getEmail() ?? '-'); ?></td>
                    <td><span class="badge badge-<?php echo $u->getPerfil(); ?>"><?php echo $u->getPerfil(); ?></span></td>
                    <td><?php echo $u->getFechaAlta(); ?></td>
                    <td>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/UsuarioController.php" style="display:inline;">
                            <input type="hidden" name="action" value="editarUsuario">
                            <input type="hidden" name="id_usuario" value="<?php echo $u->getIdUsuario(); ?>">
                            <button type="submit" class="botones btn-sm">✏️ Editar</button>
                        </form>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/UsuarioController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar el usuario <?php echo addslashes($u->getNombreUsuario()); ?>?');">
                            <input type="hidden" name="action" value="eliminarUsuario">
                            <input type="hidden" name="id_usuario" value="<?php echo $u->getIdUsuario(); ?>">
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
