<?php
require_once(__DIR__ . '/../dao/DiarioDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');

class DiarioController {

    public static function listar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        $entradas   = DiarioDAO::listarPorUsuario($id_usuario);
        include __DIR__ . '/../view/diarioListView.php';
        exit();
    }

    public static function formularioNueva() {
        $entrada = null;
        include __DIR__ . '/../view/diarioFormView.php';
        exit();
    }

    public static function formularioEditar($id_entrada) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_entrada, 'ID de entrada');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/diarioListView.php'); }

        $entrada = DiarioDAO::obtenerPorId((int)$id_entrada);
        include __DIR__ . '/../view/diarioFormView.php';
        exit();
    }

    public static function guardar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        $titulo     = trim($_POST['titulo']    ?? '');
        $contenido  = trim($_POST['contenido'] ?? '');
        $humor      = $_POST['humor'] ?? '';

        $v = new ValidadorPHP();
        $v->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 200, 'Título')
          ->requerido($humor, 'Estado de ánimo')
          ->enLista($humor, ['bien','neutral','mal'], 'Estado de ánimo')
          ->requerido($contenido, 'Contenido')
          ->minLong($contenido, 5, 'Contenido')
          ->maxLong($contenido, 5000, 'Contenido');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/diarioFormView.php'); }

        DiarioDAO::crear($id_usuario, $titulo, $contenido, $humor);
        $_SESSION['msg_ok'] = 'Entrada del diario guardada.';
        self::listar();
    }

    public static function actualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_entrada = (int)($_POST['id_entrada'] ?? 0);
        $titulo     = trim($_POST['titulo']    ?? '');
        $contenido  = trim($_POST['contenido'] ?? '');
        $humor      = $_POST['humor'] ?? '';

        $v = new ValidadorPHP();
        $v->min($id_entrada, 1, 'ID de entrada')
          ->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 200, 'Título')
          ->requerido($humor, 'Estado de ánimo')
          ->enLista($humor, ['bien','neutral','mal'], 'Estado de ánimo')
          ->requerido($contenido, 'Contenido')
          ->minLong($contenido, 5, 'Contenido')
          ->maxLong($contenido, 5000, 'Contenido');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/diarioFormView.php'); }

        DiarioDAO::actualizar($id_entrada, $titulo, $contenido, $humor);
        $_SESSION['msg_ok'] = 'Entrada actualizada.';
        self::listar();
    }

    public static function eliminar($id_entrada) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_entrada, 'ID de entrada');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/diarioListView.php'); }

        DiarioDAO::eliminar((int)$id_entrada);
        $_SESSION['msg_ok'] = 'Entrada eliminada.';
        self::listar();
    }
}

$accion = $_POST['action'] ?? $_GET['action'] ?? null;
switch ($accion) {
    case 'listar':           DiarioController::listar();           break;
    case 'formularioNueva':  DiarioController::formularioNueva();  break;
    case 'formularioEditar': DiarioController::formularioEditar($_POST['id_entrada'] ?? $_GET['id_entrada'] ?? 0); break;
    case 'guardar':          DiarioController::guardar();          break;
    case 'actualizar':       DiarioController::actualizar();       break;
    case 'eliminar':         DiarioController::eliminar($_POST['id_entrada'] ?? 0); break;
    default:                 DiarioController::listar();           break;
}
?>
