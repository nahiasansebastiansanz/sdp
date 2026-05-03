<?php
require_once(__DIR__ . '/../dao/CategoriaDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');

class CategoriaController {

    public static function listarAdmin() {
        $categorias = CategoriaDAO::listarTodas();
        include __DIR__ . '/../view/admin/categoriasView.php';
        exit();
    }

    public static function guardar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $nombre      = trim($_POST['nombre']      ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $icono       = trim($_POST['icono']       ?? '🏷️');

        $v = new ValidadorPHP();
        $v->requerido($nombre, 'Nombre')
          ->minLong($nombre, 2, 'Nombre')
          ->maxLong($nombre, 80, 'Nombre')
          ->maxLong($descripcion, 500, 'Descripción')
          ->maxLong($icono, 10, 'Icono');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/categoriasView.php'); }

        CategoriaDAO::crear($nombre, $descripcion ?: null, $icono ?: '🏷️');
        $_SESSION['msg_ok'] = 'Categoría creada.';
        self::listarAdmin();
    }

    public static function actualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_categoria = (int)($_POST['id_categoria'] ?? 0);
        $nombre       = trim($_POST['nombre']        ?? '');
        $descripcion  = trim($_POST['descripcion']   ?? '');
        $icono        = trim($_POST['icono']         ?? '🏷️');

        $v = new ValidadorPHP();
        $v->min($id_categoria, 1, 'ID de categoría')
          ->requerido($nombre, 'Nombre')
          ->minLong($nombre, 2, 'Nombre')
          ->maxLong($nombre, 80, 'Nombre')
          ->maxLong($descripcion, 500, 'Descripción')
          ->maxLong($icono, 10, 'Icono');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/categoriasView.php'); }

        CategoriaDAO::actualizar($id_categoria, $nombre, $descripcion ?: null, $icono ?: '🏷️');
        $_SESSION['msg_ok'] = 'Categoría actualizada.';
        self::listarAdmin();
    }

    public static function eliminar($id_categoria) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_categoria, 'ID de categoría');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/categoriasView.php'); }

        CategoriaDAO::eliminar((int)$id_categoria);
        $_SESSION['msg_ok'] = 'Categoría eliminada.';
        self::listarAdmin();
    }
}

$accion = $_POST['action'] ?? $_GET['action'] ?? null;
switch ($accion) {
    case 'listarAdmin': CategoriaController::listarAdmin();                              break;
    case 'guardar':     CategoriaController::guardar();                                  break;
    case 'actualizar':  CategoriaController::actualizar();                               break;
    case 'eliminar':    CategoriaController::eliminar($_POST['id_categoria'] ?? 0);      break;
    default:            CategoriaController::listarAdmin();                              break;
}
?>
