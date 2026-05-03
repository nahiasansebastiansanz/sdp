<?php
require_once(__DIR__ . '/../dao/RetoDAO.php');
require_once(__DIR__ . '/../dao/SesionDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');

class RetoController {

    public static function verRetos() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario    = $_SESSION['id_usuario'];
        self::verificarRetos($id_usuario);
        $retosActivos  = RetoDAO::listarActivos();
        $retosUsuario  = RetoDAO::retosUsuario($id_usuario);
        $ids_aceptados = array_column($retosUsuario, 'id_reto');
        include __DIR__ . '/../view/retosView.php';
        exit();
    }

    public static function aceptarReto() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        $id_reto    = (int)($_POST['id_reto'] ?? 0);

        $v = new ValidadorPHP();
        $v->min($id_reto, 1, 'Reto');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/retosView.php'); }

        RetoDAO::aceptar($id_usuario, $id_reto);
        $_SESSION['msg_ok'] = '¡Reto aceptado! Buena suerte.';
        self::verRetos();
    }

    /** Recalcular progreso de los retos aceptados por el usuario y marcar completados */
    public static function verificarRetos($id_usuario) {
        $retosUsuario = RetoDAO::retosUsuario($id_usuario);
        foreach ($retosUsuario as $ur) {
            if ($ur['completado']) continue;
            $fecha_inicio   = $ur['fecha_inicio'];
            $dias           = (int)$ur['duracion_dias'];
            $objetivo_valor = (int)$ur['objetivo_valor'];
            $progreso = 0;
            switch ($ur['tipo']) {
                case 'minutos':
                    $progreso = SesionDAO::minutosEnRango((int)$id_usuario, $fecha_inicio, $dias);
                    break;
                case 'sesiones':
                    $progreso = SesionDAO::sesionesEnRango((int)$id_usuario, $fecha_inicio, $dias);
                    break;
                case 'tipos':
                    $progreso = SesionDAO::tiposDistintosEnRango((int)$id_usuario, $fecha_inicio, $dias);
                    break;
                case 'racha':
                    $progreso = SesionDAO::diasDistintosEnRango((int)$id_usuario, $fecha_inicio, $dias);
                    break;
            }
            $progreso = min($progreso, $objetivo_valor);
            if ((int)$ur['progreso'] !== $progreso) {
                RetoDAO::actualizarProgreso((int)$id_usuario, (int)$ur['id_reto'], $progreso);
            }
            if ($progreso >= $objetivo_valor) {
                RetoDAO::marcarCompletado((int)$id_usuario, (int)$ur['id_reto']);
            }
        }
    }

    //  ADMIN 

    public static function listarAdmin() {
        $retos = RetoDAO::listarTodos();
        include __DIR__ . '/../view/admin/retosAdminView.php';
        exit();
    }

    public static function guardar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $titulo          = trim($_POST['titulo']          ?? '');
        $descripcion     = trim($_POST['descripcion']     ?? '');
        $tipo            = $_POST['tipo']                 ?? '';
        $objetivo_valor  = (int)($_POST['objetivo_valor'] ?? 0);
        $duracion_dias   = (int)($_POST['duracion_dias']  ?? 0);

        $v = new ValidadorPHP();
        $v->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 150, 'Título')
          ->maxLong($descripcion, 500, 'Descripción')
          ->requerido($tipo, 'Tipo')
          ->enLista($tipo, ['racha','minutos','sesiones','tipos'], 'Tipo')
          ->min($objetivo_valor, 1, 'Objetivo')
          ->rango($duracion_dias, 1, 365, 'Duración (días)');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/retosAdminView.php'); }

        RetoDAO::crear($titulo, $descripcion ?: null, $tipo, $objetivo_valor, $duracion_dias);
        $_SESSION['msg_ok'] = 'Reto creado correctamente.';
        self::listarAdmin();
    }

    public static function actualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_reto        = (int)($_POST['id_reto']         ?? 0);
        $titulo         = trim($_POST['titulo']           ?? '');
        $descripcion    = trim($_POST['descripcion']      ?? '');
        $tipo           = $_POST['tipo']                  ?? '';
        $objetivo_valor = (int)($_POST['objetivo_valor']  ?? 0);
        $duracion_dias  = (int)($_POST['duracion_dias']   ?? 0);
        $activo         = isset($_POST['activo']) ? 1 : 0;

        $v = new ValidadorPHP();
        $v->min($id_reto, 1, 'ID de reto')
          ->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 150, 'Título')
          ->maxLong($descripcion, 500, 'Descripción')
          ->requerido($tipo, 'Tipo')
          ->enLista($tipo, ['racha','minutos','sesiones','tipos'], 'Tipo')
          ->min($objetivo_valor, 1, 'Objetivo')
          ->rango($duracion_dias, 1, 365, 'Duración (días)');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/retosAdminView.php'); }

        RetoDAO::actualizar($id_reto, $titulo, $descripcion ?: null, $tipo, $objetivo_valor, $duracion_dias, $activo);
        $_SESSION['msg_ok'] = 'Reto actualizado.';
        self::listarAdmin();
    }

    public static function eliminar($id_reto) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_reto, 'ID de reto');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/retosAdminView.php'); }

        RetoDAO::eliminar((int)$id_reto);
        $_SESSION['msg_ok'] = 'Reto eliminado.';
        self::listarAdmin();
    }
}


if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $accion = $_POST['action'] ?? $_GET['action'] ?? null;
    switch ($accion) {
        case 'verRetos':    RetoController::verRetos();                     break;
        case 'aceptarReto': RetoController::aceptarReto();                  break;
        case 'listarAdmin': RetoController::listarAdmin();                  break;
        case 'guardar':     RetoController::guardar();                      break;
        case 'actualizar':  RetoController::actualizar();                   break;
        case 'eliminar':    RetoController::eliminar($_POST['id_reto'] ?? 0); break;
        default:            RetoController::verRetos();                     break;
    }
}
?>
