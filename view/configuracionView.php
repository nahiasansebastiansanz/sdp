<?php
include __DIR__ . '/../view/statics/cabeceraView.php';
require_once(__DIR__ . '/../dao/UsuarioDAO.php');
$usuario = UsuarioDAO::obtenerPorId($_SESSION['id_usuario']);
?>

<main>
    <h1>⚙️ Configuración</h1>
    <p class="subtitulo">Gestiona tu perfil y preferencias</p>

    <form id="perfilForm" method="POST" action="../controller/UsuarioController.php">
        <input type="hidden" name="action" value="actualizarPerfil">
        <div class="formularios formularios-bloques">
            <div class="campo">
                <label for="nombre_completo">Nombre completo: *</label>
                <input type="text" id="nombre_completo" name="nombre_completo" required
                       value="<?php echo htmlspecialchars($usuario->getNombreCompleto()); ?>">
            </div>

            <div class="campo">
                <label for="nombre_usuario">Usuario: *</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required
                       value="<?php echo htmlspecialchars($usuario->getNombreUsuario()); ?>">
            </div>

            <div class="campo">
                <label for="email">Email: *</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo htmlspecialchars($usuario->getEmail() ?? ''); ?>">
            </div>

            <div class="campo">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" min="5" max="120"
                       value="<?php echo htmlspecialchars($usuario->getEdad() ?? ''); ?>">
            </div>

            <div class="campo">
                <label for="genero">Género:</label>
                <select id="genero" name="genero">
                    <option value="">Prefiero no decir</option>
                    <option value="Hombre" <?php echo $usuario->getGenero() == 'Hombre' ? 'selected' : ''; ?>>Hombre</option>
                    <option value="Mujer"  <?php echo $usuario->getGenero() == 'Mujer'  ? 'selected' : ''; ?>>Mujer</option>
                    <option value="Otro"   <?php echo $usuario->getGenero() == 'Otro'   ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>

            <div class="campo">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono"
                       value="<?php echo htmlspecialchars($usuario->getTelefono() ?? ''); ?>">
            </div>
        </div>
    </form>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">Cancelar</button>
        <button type="submit" form="perfilForm" class="botones botones-primario">💾 Guardar cambios</button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
