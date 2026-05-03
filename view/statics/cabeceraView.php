<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$projectRoot = dirname(dirname(__DIR__));
$projectName = basename($projectRoot);
$cssPath     = '/' . $projectName . '/assets/css/styles.css';
$jsPath      = '/' . $projectName . '/assets/js/main.js';
$imgPath     = '/' . $projectName . '/assets/img/logo.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $cssPath; ?>">
    <title>SDP – Serenity & Deep Peace</title>

    <!-- jQuery 3.7.1 + jQuery Validate 1.19.5 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <!-- Validadores POO SDP -->
    <script src="<?php echo $jsPath; ?>" defer></script>
</head>
<body>

<div class="contenedor">

    <header>
        <div class="logo">
            <span class="logo-texto">🧘 <strong>SDP</strong></span>
        </div>

        <?php if (isset($_SESSION['nombre_usuario']) && !empty($_SESSION['nombre_usuario'])): ?>
        <nav class="nav-principal">
            <?php if ($_SESSION['perfil_usuario'] === 'admin'): ?>
                <a href="/<?php echo $projectName; ?>/controller/UsuarioController.php">Panel Admin</a>
                <a href="/<?php echo $projectName; ?>/controller/MeditacionController.php?action=listarAdmin">Meditaciones</a>
                <a href="/<?php echo $projectName; ?>/controller/CategoriaController.php?action=listarAdmin">Categorías</a>
                <a href="/<?php echo $projectName; ?>/controller/RespiracionController.php?action=listarAdmin">Respiraciones</a>
                <a href="/<?php echo $projectName; ?>/controller/LogroController.php?action=listarAdmin">Logros</a>
                <a href="/<?php echo $projectName; ?>/controller/RetoController.php?action=listarAdmin">Retos</a>
                <a href="/<?php echo $projectName; ?>/controller/UsuarioController.php?action=listarMensajesContacto">Mensajes contacto</a>
            <?php else: ?>
                <a href="/<?php echo $projectName; ?>/controller/UsuarioController.php">Inicio</a>
                <a href="/<?php echo $projectName; ?>/controller/MeditacionController.php?action=seleccionar">Meditar</a>
                <a href="/<?php echo $projectName; ?>/controller/SesionController.php?action=verEstadisticas">Estadísticas</a>
                <a href="/<?php echo $projectName; ?>/controller/DiarioController.php?action=listar">Diario</a>
                <a href="/<?php echo $projectName; ?>/controller/RetoController.php?action=verRetos">Retos</a>
                <a href="/<?php echo $projectName; ?>/controller/LogroController.php?action=verLogros">Logros</a>
                <a href="/<?php echo $projectName; ?>/view/contactoView.php">Contacto</a>
            <?php endif; ?>
        </nav>

        <div class="info-usuario">
            👤 <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?> |
            <a href="/<?php echo $projectName; ?>/view/configuracionView.php">Configuración</a> |
            <a href="/<?php echo $projectName; ?>/controller/MeditacionController.php?action=verFavoritos">Favoritos</a> |
            <a href="/<?php echo $projectName; ?>/controller/SesionController.php?action=verHistorial">Historial</a>
        </div>
        <?php endif; ?>
    </header>

    <!--  Mensajes flash (éxito)  -->
    <?php if (isset($_SESSION['msg_ok'])): ?>
        <div class="alerta alerta-ok"><?php echo htmlspecialchars($_SESSION['msg_ok']); ?></div>
        <?php unset($_SESSION['msg_ok']); ?>
    <?php endif; ?>

    <!--  Errores simples de sesión  -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alerta alerta-error">
            <?php
            $msg = 'Ha ocurrido un error.';
            switch ($_SESSION['error']) {
                case 'credenciales':
                    $msg = 'Usuario o contraseña incorrectos.';
                    break;
                case 'registro':
                    $msg = 'No se pudo crear la cuenta. El usuario puede estar en uso.';
                    break;
                case 'perfil':
                    $msg = 'No se pudo actualizar el perfil.';
                    break;
            }
            echo $msg;
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <!--  Errores de validación PHP (ValidadorPHP)  -->
    <?php if (!empty($_SESSION['errores_php'])): ?>
        <div class="errores-servidor">
            <strong>⚠️ Por favor corrige los siguientes errores:</strong>
            <ul>
                <?php foreach ($_SESSION['errores_php'] as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores_php']); ?>
    <?php endif; ?>
