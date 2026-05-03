<?php
require_once(__DIR__ . '/../dao/RespiracionDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');

class RespiracionController {

    public static function listar() {
        $respiraciones = RespiracionDAO::listarTodas();
        include __DIR__ . '/../view/respiracionView.php';
        exit();
    }

    public static function listarAdmin() {
        $respiraciones = RespiracionDAO::listarTodas();
        include __DIR__ . '/../view/admin/respiracionesView.php';
        exit();
    }

    public static function formularioNueva() {
        $respiracion = null;
        include __DIR__ . '/../view/admin/respiracionFormView.php';
        exit();
    }

    public static function formularioEditar($id_respiracion) {
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_respiracion, 'ID de respiración');
        if ($v->hayErrores()) { if (session_status() === PHP_SESSION_NONE) session_start(); $v->redirigirConErrores('../view/admin/respiracionesView.php'); }

        $respiracion = RespiracionDAO::obtenerPorId((int)$id_respiracion);
        include __DIR__ . '/../view/admin/respiracionFormView.php';
        exit();
    }

    public static function guardar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $nombre      = trim($_POST['nombre']       ?? '');
        $descripcion = trim($_POST['descripcion']  ?? '');
        $inhala      = (int)($_POST['inhala_seg']   ?? 0);
        $retiene     = (int)($_POST['retiene_seg']  ?? 0);
        $exhala      = (int)($_POST['exhala_seg']   ?? 0);
        $retiene2    = (int)($_POST['retiene2_seg'] ?? 0);
        $ciclos      = (int)($_POST['ciclos']       ?? 0);

        $v = new ValidadorPHP();
        $v->requerido($nombre, 'Nombre')
          ->minLong($nombre, 3, 'Nombre')
          ->maxLong($nombre, 120, 'Nombre')
          ->maxLong($descripcion, 500, 'Descripción')
          ->rango($inhala,   1, 60, 'Inhala (seg)')
          ->rango($exhala,   1, 60, 'Exhala (seg)')
          ->rango($ciclos,   1, 60, 'Ciclos')
          ->rango($retiene,  0, 60, 'Retención 1 (seg)')
          ->rango($retiene2, 0, 60, 'Retención 2 (seg)');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/respiracionFormView.php'); }

        RespiracionDAO::crear($nombre, $descripcion ?: null, $inhala, $retiene, $exhala, $retiene2, $ciclos);
        $_SESSION['msg_ok'] = 'Técnica de respiración creada.';
        self::listarAdmin();
    }

    public static function actualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id          = (int)($_POST['id_respiracion'] ?? 0);
        $nombre      = trim($_POST['nombre']          ?? '');
        $descripcion = trim($_POST['descripcion']     ?? '');
        $inhala      = (int)($_POST['inhala_seg']     ?? 0);
        $retiene     = (int)($_POST['retiene_seg']    ?? 0);
        $exhala      = (int)($_POST['exhala_seg']     ?? 0);
        $retiene2    = (int)($_POST['retiene2_seg']   ?? 0);
        $ciclos      = (int)($_POST['ciclos']         ?? 0);

        $v = new ValidadorPHP();
        $v->min($id, 1, 'ID de respiración')
          ->requerido($nombre, 'Nombre')
          ->minLong($nombre, 3, 'Nombre')
          ->maxLong($nombre, 120, 'Nombre')
          ->maxLong($descripcion, 500, 'Descripción')
          ->rango($inhala,   1, 60, 'Inhala (seg)')
          ->rango($exhala,   1, 60, 'Exhala (seg)')
          ->rango($ciclos,   1, 60, 'Ciclos')
          ->rango($retiene,  0, 60, 'Retención 1 (seg)')
          ->rango($retiene2, 0, 60, 'Retención 2 (seg)');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/respiracionFormView.php'); }

        RespiracionDAO::actualizar($id, $nombre, $descripcion ?: null, $inhala, $retiene, $exhala, $retiene2, $ciclos);
        $_SESSION['msg_ok'] = 'Técnica de respiración actualizada.';
        self::listarAdmin();
    }

    public static function eliminar($id_respiracion) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_respiracion, 'ID de respiración');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/respiracionesView.php'); }

        RespiracionDAO::eliminar((int)$id_respiracion);
        $_SESSION['msg_ok'] = 'Técnica de respiración eliminada.';
        self::listarAdmin();
    }
}

$accion = $_POST['action'] ?? $_GET['action'] ?? null;
switch ($accion) {
    case 'listar':           RespiracionController::listar();                                                                     break;
    case 'listarAdmin':      RespiracionController::listarAdmin();                                                                break;
    case 'formularioNueva':  RespiracionController::formularioNueva();                                                            break;
    case 'formularioEditar': RespiracionController::formularioEditar($_POST['id_respiracion'] ?? $_GET['id_respiracion'] ?? 0);   break;
    case 'guardar':          RespiracionController::guardar();                                                                    break;
    case 'actualizar':       RespiracionController::actualizar();                                                                 break;
    case 'eliminar':         RespiracionController::eliminar($_POST['id_respiracion'] ?? 0);                                      break;
    default:                 RespiracionController::listar();                                                                     break;
}
?>
