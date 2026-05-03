<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>Crear cuenta</h1>
    <p class="subtitulo">Únete a SDP y empieza a meditar hoy mismo</p>

    <form id="registroForm" method="POST" action="../controller/UsuarioController.php">
        <input type="hidden" name="action" value="registrar">

        <div class="formularios-grid">
            <div class="form-grupo">
                <label for="nombre_completo">Nombre completo: *</label>
                <input type="text" id="nombre_completo" name="nombre_completo" required>
            </div>

            <div class="form-grupo">
                <label for="nombre_usuario">Usuario: *</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required>
            </div>

            <div class="form-grupo">
                <label for="email">Email: *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-grupo">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" min="5" max="120" placeholder="Opcional">
            </div>

            <div class="form-grupo">
                <label for="genero">Género:</label>
                <select id="genero" name="genero">
                    <option value="">Prefiero no decir</option>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="form-grupo">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Opcional">
            </div>

            <div class="form-grupo">
                <label for="contrasena">Contraseña: *</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>

            <div class="form-grupo">
                <label for="confirmar">Confirmar contraseña: *</label>
                <input type="password" id="confirmar" name="confirmar" required>
            </div>
        </div>
    </form>

    <div class="dashboardActions">
        <button class="botones" onclick="window.location.href='loginView.php'">Volver al login</button>
        <button type="submit" form="registroForm" class="botones botones-primario">Crear cuenta</button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
