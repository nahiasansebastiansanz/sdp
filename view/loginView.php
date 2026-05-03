<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>Acceso a SDP</h1>
    <p class="subtitulo">Inicia sesión para continuar tu práctica de meditación</p>

    <form id="loginForm" method="POST" action="../controller/UsuarioController.php">
        <input type="hidden" name="action" value="login">
        <div class="formularios">
            <label for="nombre_usuario">Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
    </form>

    <div class="dashboardActions">
        <button class="botones" onclick="window.location.href='registroView.php'">Registrarse</button>
        <button type="submit" form="loginForm" class="botones botones-primario">Iniciar Sesión</button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
