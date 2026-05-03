<?php
include __DIR__ . '/../view/statics/cabeceraView.php';

$perfil = $_SESSION['perfil_usuario'] ?? 'usuario';
?>

<main>
<?php if ($perfil === 'admin'): ?>

    <!--  PANEL ADMIN  -->
    <h1>Panel de Administración – SDP</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-valor"><?php echo $totalSesiones ?? '-'; ?></div>
            <div class="stat-etiqueta">Sesiones registradas</div>
        </div>
    </div>

    <div class="botonera">
        <form id="gestionUsuariosForm" method="POST" action="../controller/UsuarioController.php">
            <input type="hidden" name="action" value="listarUsuarios">
        </form>
        <button type="submit" form="gestionUsuariosForm" class="botones">👥 Gestión Usuarios</button>

        <form id="gestionMedForm" method="POST" action="../controller/MeditacionController.php">
            <input type="hidden" name="action" value="listarAdmin">
        </form>
        <button type="submit" form="gestionMedForm" class="botones">🧘 Gestión Meditaciones</button>

        <form id="gestionCatForm" method="POST" action="../controller/CategoriaController.php">
            <input type="hidden" name="action" value="listarAdmin">
        </form>
        <button type="submit" form="gestionCatForm" class="botones">🏷️ Gestión Categorías</button>

        <form id="gestionRespForm" method="POST" action="../controller/RespiracionController.php">
            <input type="hidden" name="action" value="listarAdmin">
        </form>
        <button type="submit" form="gestionRespForm" class="botones">🌬️ Gestión Respiraciones</button>

        <form id="gestionLogroForm" method="POST" action="../controller/LogroController.php">
            <input type="hidden" name="action" value="listarAdmin">
        </form>
        <button type="submit" form="gestionLogroForm" class="botones">🏅 Gestión Logros</button>

        <form id="gestionRetoForm" method="POST" action="../controller/RetoController.php">
            <input type="hidden" name="action" value="listarAdmin">
        </form>
        <button type="submit" form="gestionRetoForm" class="botones">🏆 Gestión Retos</button>
    </div>

<?php else: ?>

    <!--  DASHBOARD USUARIO  -->
    <h1>Bienvenido/a, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?> 🧘</h1>

    <!-- Resumen rápido -->
    <div class="stats-grid">
        <div class="stat-card stat-azul">
            <div class="stat-valor"><?php echo $minutosHoy ?? 0; ?></div>
            <div class="stat-etiqueta">min hoy</div>
        </div>
        <div class="stat-card stat-arena">
            <div class="stat-valor"><?php echo $minutosSem ?? 0; ?></div>
            <div class="stat-etiqueta">min esta semana</div>
        </div>
        <div class="stat-card stat-verde">
            <div class="stat-valor"><?php echo $racha ?? 0; ?> 🔥</div>
            <div class="stat-etiqueta">días de racha</div>
        </div>
        <div class="stat-card stat-azul">
            <div class="stat-valor"><?php echo $totalSesiones ?? 0; ?></div>
            <div class="stat-etiqueta">sesiones totales</div>
        </div>
    </div>

    <!-- Acciones principales -->
    <div class="botonera">
        <form id="medSelForm" method="POST" action="../controller/MeditacionController.php">
            <input type="hidden" name="action" value="seleccionar">
        </form>
        <button type="submit" form="medSelForm" class="botones botones-primario">▶ Empezar a meditar</button>

        <form id="statsForm" method="POST" action="../controller/SesionController.php">
            <input type="hidden" name="action" value="verEstadisticas">
        </form>
        <button type="submit" form="statsForm" class="botones">📊 Mis estadísticas</button>

        <form id="diarioForm" method="POST" action="../controller/DiarioController.php">
            <input type="hidden" name="action" value="listar">
        </form>
        <button type="submit" form="diarioForm" class="botones">📝 Mi diario</button>

        <form id="retosForm" method="POST" action="../controller/RetoController.php">
            <input type="hidden" name="action" value="verRetos">
        </form>
        <button type="submit" form="retosForm" class="botones">🏆 Retos</button>

        <form id="logrosForm" method="POST" action="../controller/LogroController.php">
            <input type="hidden" name="action" value="verLogros">
        </form>
        <button type="submit" form="logrosForm" class="botones">🏅 Logros</button>
    </div>

    <!-- Frase motivacional -->
    <div class="frase-bloque">
        <blockquote>"La práctica diaria no tiene que ser perfecta. Solo tiene que existir."</blockquote>
    </div>

<?php endif; ?>

    <!-- Cerrar sesión -->
    <div class="dashboardActions">
        <form id="logoutForm" method="POST" action="../controller/UsuarioController.php">
            <input type="hidden" name="action" value="logout">
        </form>
        <button type="submit" form="logoutForm" class="botones botones-peligro">Cerrar sesión</button>
    </div>
</main>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
