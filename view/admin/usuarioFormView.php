<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1><?php echo $usuario ? '✏️ Editar usuario' : '👤 Nuevo usuario'; ?></h1>

    <form id="usuarioForm" method="POST" action="/<?php echo $projectName; ?>/controller/UsuarioController.php">
        <input type="hidden" name="action" value="<?php echo $usuario ? 'actualizarUsuario' : 'crearUsuarioAdmin'; ?>">
        <?php if ($usuario): ?>
            <input type="hidden" name="id_usuario" value="<?php echo $usuario->getIdUsuario(); ?>">
        <?php endif; ?>

        <div class="formularios">
            <label for="nombre_completo">Nombre completo: *</label>
            <input type="text" id="nombre_completo" name="nombre_completo" required
                   value="<?php echo htmlspecialchars($usuario ? $usuario->getNombreCompleto() : ''); ?>">

            <label for="nombre_usuario">Usuario: *</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required
                   value="<?php echo htmlspecialchars($usuario ? $usuario->getNombreUsuario() : ''); ?>">

            <label for="email">Email: *</label>
            <input type="email" id="email" name="email" required
                   value="<?php echo htmlspecialchars($usuario ? ($usuario->getEmail() ?? '') : ''); ?>">

            <label for="perfil">Perfil: *</label>
            <select id="perfil" name="perfil" required>
                <option value="usuario" <?php echo (!$usuario || $usuario->getPerfil() == 'usuario') ? 'selected' : ''; ?>>usuario</option>
                <option value="admin"   <?php echo ($usuario && $usuario->getPerfil() == 'admin')   ? 'selected' : ''; ?>>admin</option>
            </select>

            <?php if (!$usuario): ?>
            <label for="contrasena">Contraseña: *</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <?php endif; ?>
        </div>
    </form>

    <div class="dashboardActions">
        <a href="/<?php echo $projectName; ?>/controller/UsuarioController.php?action=listarUsuarios" class="botones">Cancelar</a>
        <button type="submit" form="usuarioForm" class="botones botones-primario">
            <?php echo $usuario ? '💾 Actualizar' : '➕ Crear usuario'; ?>
        </button>
    </div>
</main>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
