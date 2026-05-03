<?php
require_once(__DIR__ . '/../dao/SesionDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');
require_once(__DIR__ . '/RetoController.php');
require_once(__DIR__ . '/LogroController.php');

class SesionController {

    public static function registrar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario    = $_SESSION['id_usuario'];
        $tipo          = $_POST['tipo']          ?? '';
        $duracion_min  = (int)($_POST['duracion_min'] ?? 0);
        $id_meditacion = $_POST['id_meditacion'] !== '' ? (int)$_POST['id_meditacion'] : null;
        $con_gong      = isset($_POST['con_gong']) ? 1 : 0;

        $v = new ValidadorPHP();
        $v->requerido($tipo, 'Tipo de sesión')
          ->enLista($tipo, ['libre','guiada','respiracion'], 'Tipo de sesión')
          ->rango($duracion_min, 1, 720, 'Duración (min)');

        if ($id_meditacion !== null) {
            $v->min($id_meditacion, 1, 'Meditación');
        }

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/homeView.php'); }

        SesionDAO::registrar($id_usuario, $tipo, $duracion_min, $id_meditacion, $con_gong);
        RetoController::verificarRetos($id_usuario);
        LogroController::verificarLogros($id_usuario);
        $_SESSION['msg_ok'] = "¡Sesión de {$duracion_min} min registrada!";
        header('Location: ../index.php');
        exit();
    }

    public static function verHistorial() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        $sesiones   = SesionDAO::historialPorUsuario($id_usuario);
        include __DIR__ . '/../view/historialView.php';
        exit();
    }

    public static function eliminarSesion($id_sesion) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_sesion, 'ID de sesión');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/historialView.php'); }

        SesionDAO::eliminar((int)$id_sesion);
        $_SESSION['msg_ok'] = 'Sesión eliminada del historial.';
        self::verHistorial();
    }

    public static function verEstadisticas() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario    = $_SESSION['id_usuario'];
        $minutosHoy    = SesionDAO::minutosHoy($id_usuario);
        $minutosSem    = SesionDAO::minutosSemana($id_usuario);
        $minutosMes    = SesionDAO::minutosMes($id_usuario);
        $minutosTotal  = SesionDAO::minutosTotal($id_usuario);
        $racha         = SesionDAO::rachaActual($id_usuario);
        $totalSesiones = SesionDAO::totalSesiones($id_usuario);
        $datosSemana   = SesionDAO::minutosPorDia7($id_usuario);
        $datosTipos    = SesionDAO::distribucionTipos($id_usuario);
        include __DIR__ . '/../view/estadisticasView.php';
        exit();
    }
}

$accion = $_POST['action'] ?? $_GET['action'] ?? null;
switch ($accion) {
    case 'registrar':      SesionController::registrar();                              break;
    case 'verHistorial':   SesionController::verHistorial();                           break;
    case 'eliminarSesion': SesionController::eliminarSesion($_POST['id_sesion'] ?? 0); break;
    case 'verEstadisticas':SesionController::verEstadisticas();                        break;
    default:               SesionController::verHistorial();                           break;
}
?>
