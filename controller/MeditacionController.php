<?php
require_once(__DIR__ . '/../dao/MeditacionDAO.php');
require_once(__DIR__ . '/../dao/CategoriaDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');

class MeditacionController {

    public static function seleccionar() {
        include __DIR__ . '/../view/meditacion/selectView.php';
        exit();
    }

    public static function libre() {
        include __DIR__ . '/../view/meditacion/libreView.php';
        exit();
    }

    public static function guiada() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario   = $_SESSION['id_usuario'];
        $categorias   = CategoriaDAO::listarTodas();
        $meditaciones = MeditacionDAO::listarTodas();
        $favoritos    = MeditacionDAO::listarFavoritos($id_usuario);
        $ids_fav      = array_map(fn($f) => $f->getIdMeditacion(), $favoritos);
        include __DIR__ . '/../view/meditacion/guiadaView.php';
        exit();
    }

    public static function toggleFavorito() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario    = $_SESSION['id_usuario'];
        $id_meditacion = (int)($_POST['id_meditacion'] ?? 0);
        $accion_fav    = $_POST['accion_fav'] ?? '';

        $v = new ValidadorPHP();
        $v->min($id_meditacion, 1, 'Meditación')
          ->enLista($accion_fav, ['añadir','quitar'], 'Acción');
        if ($v->hayErrores()) { $v->redirigirConErrores('MeditacionController.php?action=guiada'); }

        if ($accion_fav === 'añadir') {
            MeditacionDAO::añadirFavorito($id_usuario, $id_meditacion);
        } else {
            MeditacionDAO::quitarFavorito($id_usuario, $id_meditacion);
        }
        header('Location: MeditacionController.php?action=guiada');
        exit();
    }

    public static function verFavoritos() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        $favoritos  = MeditacionDAO::listarFavoritos($id_usuario);
        include __DIR__ . '/../view/favoritosView.php';
        exit();
    }

    //  ADMIN 

    public static function listarAdmin() {
        $meditaciones = MeditacionDAO::listarTodas();
        $categorias   = CategoriaDAO::listarTodas();
        include __DIR__ . '/../view/admin/meditacionesView.php';
        exit();
    }

    public static function formularioNueva() {
        $categorias = CategoriaDAO::listarTodas();
        $meditacion = null;
        include __DIR__ . '/../view/admin/meditacionFormView.php';
        exit();
    }

    public static function formularioEditar($id_meditacion) {
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_meditacion, 'ID de meditación');
        if ($v->hayErrores()) { if (session_status() === PHP_SESSION_NONE) session_start(); $v->redirigirConErrores('../view/admin/meditacionesView.php'); }

        $categorias = CategoriaDAO::listarTodas();
        $meditacion = MeditacionDAO::obtenerPorId((int)$id_meditacion);
        include __DIR__ . '/../view/admin/meditacionFormView.php';
        exit();
    }

    public static function guardar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $titulo        = trim($_POST['titulo']        ?? '');
        $descripcion   = trim($_POST['descripcion']   ?? '');
        $id_categoria  = (int)($_POST['id_categoria'] ?? 0);
        $nivel         = $_POST['nivel']              ?? '';
        $duracion_min  = (int)($_POST['duracion_min'] ?? 0);
        $icono         = $_POST['icono']              ?? '🧘';
        $archivo_audio = $_POST['archivo_audio']      ?? null;
        $instrucciones = $_POST['instrucciones']      ?? null;

        $v = new ValidadorPHP();
        $v->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 150, 'Título')
          ->maxLong($descripcion, 1000, 'Descripción')
          ->min($id_categoria, 1, 'Categoría')
          ->requerido($nivel, 'Nivel')
          ->enLista($nivel, ['principiante','intermedio','avanzado'], 'Nivel')
          ->rango($duracion_min, 1, 180, 'Duración');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/meditacionFormView.php'); }

        MeditacionDAO::crear($titulo, $descripcion ?: null, $id_categoria, $nivel,
                             $duracion_min, $icono ?: '🧘', $archivo_audio ?: null, $instrucciones ?: null);
        $_SESSION['msg_ok'] = 'Meditación creada correctamente.';
        self::listarAdmin();
    }

    public static function actualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_meditacion = (int)($_POST['id_meditacion'] ?? 0);
        $titulo        = trim($_POST['titulo']         ?? '');
        $descripcion   = trim($_POST['descripcion']    ?? '');
        $id_categoria  = (int)($_POST['id_categoria']  ?? 0);
        $nivel         = $_POST['nivel']               ?? '';
        $duracion_min  = (int)($_POST['duracion_min']  ?? 0);
        $icono         = $_POST['icono']               ?? '🧘';
        $instrucciones = $_POST['instrucciones']       ?? null;

        $v = new ValidadorPHP();
        $v->min($id_meditacion, 1, 'ID de meditación')
          ->requerido($titulo, 'Título')
          ->minLong($titulo, 3, 'Título')
          ->maxLong($titulo, 150, 'Título')
          ->maxLong($descripcion, 1000, 'Descripción')
          ->min($id_categoria, 1, 'Categoría')
          ->requerido($nivel, 'Nivel')
          ->enLista($nivel, ['principiante','intermedio','avanzado'], 'Nivel')
          ->rango($duracion_min, 1, 180, 'Duración');

        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/meditacionFormView.php'); }

        MeditacionDAO::actualizar($id_meditacion, $titulo, $descripcion ?: null, $id_categoria,
                                  $nivel, $duracion_min, $icono ?: '🧘', $instrucciones ?: null);
        $_SESSION['msg_ok'] = 'Meditación actualizada.';
        self::listarAdmin();
    }

    public static function eliminar($id_meditacion) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_meditacion, 'ID de meditación');
        if ($v->hayErrores()) { $v->redirigirConErrores('../view/admin/meditacionesView.php'); }

        MeditacionDAO::eliminar((int)$id_meditacion);
        $_SESSION['msg_ok'] = 'Meditación eliminada.';
        self::listarAdmin();
    }
}

$accion = $_POST['action'] ?? $_GET['action'] ?? null;
switch ($accion) {
    case 'seleccionar':      MeditacionController::seleccionar();                                               break;
    case 'libre':            MeditacionController::libre();                                                     break;
    case 'guiada':           MeditacionController::guiada();                                                    break;
    case 'toggleFavorito':   MeditacionController::toggleFavorito();                                            break;
    case 'verFavoritos':     MeditacionController::verFavoritos();                                              break;
    case 'listarAdmin':      MeditacionController::listarAdmin();                                               break;
    case 'formularioNueva':  MeditacionController::formularioNueva();                                           break;
    case 'formularioEditar': MeditacionController::formularioEditar($_POST['id_meditacion'] ?? $_GET['id_meditacion'] ?? 0); break;
    case 'guardar':          MeditacionController::guardar();                                                   break;
    case 'actualizar':       MeditacionController::actualizar();                                                break;
    case 'eliminar':         MeditacionController::eliminar($_POST['id_meditacion'] ?? 0);                      break;
    default:                 MeditacionController::seleccionar();                                               break;
}
?>
