<?php
require_once(__DIR__ . '/../dao/LogroDAO.php');
require_once(__DIR__ . '/../dao/SesionDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');

class LogroController {

    public static function verLogros() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario    = $_SESSION['id_usuario'];
        self::verificarLogros($id_usuario);
        $todosLogros   = LogroDAO::listarTodos();
        $obtenidos     = LogroDAO::logrosObtenidos($id_usuario);
        $ids_obtenidos = array_column($obtenidos, 'id_logro');
        include __DIR__ . '/../view/logrosView.php';
        exit();
    }

    /** Verificar y conceder logros automáticamente tras una sesión */
    public static function verificarLogros($id_usuario) {
        $logros        = LogroDAO::listarTodos();
        $ids_obtenidos = array_column(LogroDAO::logrosObtenidos($id_usuario), 'id_logro');
        $sesiones      = SesionDAO::totalSesiones($id_usuario);
        $minutos       = SesionDAO::minutosTotal($id_usuario);
        $racha         = SesionDAO::rachaActual($id_usuario);

        foreach ($logros as $logro) {
            if (in_array($logro->getIdLogro(), $ids_obtenidos)) continue;
            $cumple = false;
            switch ($logro->getCondicionTipo()) {
                case 'sesiones':
                    $cumple = $sesiones >= $logro->getCondicionValor();
                    break;
                case 'minutos':
                    $cumple = $minutos >= $logro->getCondicionValor();
                    break;
                case 'racha':
                    $cumple = $racha >= $logro->getCondicionValor();
                    break;
                default:
                    $cumple = false;
            }
            if ($cumple) LogroDAO::conceder($id_usuario, $logro->getIdLogro());
        }
    }

    //  ADMIN 

    public static function listarAdmin() {
        $logros = LogroDAO::listarTodos();
        include __DIR__ . '/../view/admin/logrosAdminView.php';
        exit();
    }

    public static function guardar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $titulo          = trim($_POST['titulo']          ?? '');
        $descripcion     = trim($_POST['descripcion']     ?? '');
        $icono           = trim($_POST['icono']           ?? '🏅');
        $condicion_tipo  = $_POST['condicion_tipo']       ?? '';
        $condicion_valor = (int)($_POST['condicion_valor'] ?? 0);

        $v = new ValidadorPHP();
        $v->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 120, 'Título')
          ->maxLong($descripcion, 500, 'Descripción')
          ->maxLong($icono, 10, 'Icono')
          ->requerido($condicion_tipo, 'Tipo de condición')
          ->enLista($condicion_tipo, ['sesiones','minutos','racha'], 'Tipo de condición')
          ->min($condicion_valor, 1, 'Valor de condición');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/logrosAdminView.php'); }

        LogroDAO::crear($titulo, $descripcion ?: null, $icono ?: '🏅', $condicion_tipo, $condicion_valor);
        $_SESSION['msg_ok'] = 'Logro creado correctamente.';
        self::listarAdmin();
    }

    public static function actualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_logro        = (int)($_POST['id_logro']        ?? 0);
        $titulo          = trim($_POST['titulo']           ?? '');
        $descripcion     = trim($_POST['descripcion']      ?? '');
        $icono           = trim($_POST['icono']            ?? '🏅');
        $condicion_tipo  = $_POST['condicion_tipo']        ?? '';
        $condicion_valor = (int)($_POST['condicion_valor'] ?? 0);

        $v = new ValidadorPHP();
        $v->min($id_logro, 1, 'ID de logro')
          ->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 120, 'Título')
          ->maxLong($descripcion, 500, 'Descripción')
          ->requerido($condicion_tipo, 'Tipo de condición')
          ->enLista($condicion_tipo, ['sesiones','minutos','racha'], 'Tipo de condición')
          ->min($condicion_valor, 1, 'Valor de condición');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/logrosAdminView.php'); }

        LogroDAO::actualizar($id_logro, $titulo, $descripcion ?: null, $icono ?: '🏅', $condicion_tipo, $condicion_valor);
        $_SESSION['msg_ok'] = 'Logro actualizado.';
        self::listarAdmin();
    }

    public static function eliminar($id_logro) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_logro, 'ID de logro');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/logrosAdminView.php'); }

        LogroDAO::eliminar((int)$id_logro);
        $_SESSION['msg_ok'] = 'Logro eliminado.';
        self::listarAdmin();
    }
}


if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $accion = $_POST['action'] ?? $_GET['action'] ?? null;
    switch ($accion) {
        case 'verLogros':   LogroController::verLogros();                   break;
        case 'listarAdmin': LogroController::listarAdmin();                 break;
        case 'guardar':     LogroController::guardar();                     break;
        case 'actualizar':  LogroController::actualizar();                  break;
        case 'eliminar':    LogroController::eliminar($_POST['id_logro'] ?? 0); break;
        default:            LogroController::verLogros();                   break;
    }
}
?>
