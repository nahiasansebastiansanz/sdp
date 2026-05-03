<?php
require_once(__DIR__ . '/../dao/UsuarioDAO.php');
require_once(__DIR__ . '/../dao/SesionDAO.php');
require_once(__DIR__ . '/../dao/ContactoDAO.php');
require_once(__DIR__ . '/../util/ValidadorPHP.php');
require_once(__DIR__ . '/../util/OsTicketClient.php');

class UsuarioController {

    //  LOGIN 
    public static function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $v = new ValidadorPHP();
        $v->requerido($_POST['nombre_usuario'] ?? '', 'Usuario')
          ->minLong($_POST['nombre_usuario'] ?? '', 3, 'Usuario')
          ->requerido($_POST['contrasena'] ?? '', 'Contraseña')
          ->minLong($_POST['contrasena'] ?? '', 6, 'Contraseña');

        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/loginView.php');
        }

        $nombre_usuario = trim($_POST['nombre_usuario']);
        $contrasena     = $_POST['contrasena'];

        $usuario = UsuarioDAO::obtenerPorNombreUsuario($nombre_usuario);
        if ($usuario && $usuario->getContrasenaHash() === hash('sha256', $contrasena)) {
            $_SESSION['id_usuario']       = $usuario->getIdUsuario();
            $_SESSION['nombre_usuario']   = $usuario->getNombreUsuario();
            $_SESSION['nombre_completo']  = $usuario->getNombreCompleto();
            $_SESSION['email']            = $usuario->getEmail();
            $_SESSION['perfil_usuario']   = $usuario->getPerfil();
            $_SESSION['ultima_actividad'] = time();
            unset($_SESSION['error']);
            header('Location: ../index.php');
        } else {
            $_SESSION['error'] = 'credenciales';
            header('Location: ../view/loginView.php');
        }
        exit();
    }

    //  LOGOUT 
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header('Location: ../view/loginView.php');
        exit();
    }

    //  VERIFICAR SESIÓN -> carga dashboard 
    public static function verificarSesion() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $sesionActiva = true;

        if (!isset($_SESSION['nombre_usuario']) || empty($_SESSION['nombre_usuario'])) {
            $sesionActiva = false;
        }

        $temporizador = 1800;
        if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > $temporizador)) {
            session_unset();
            session_destroy();
            $sesionActiva = false;
        }
        $_SESSION['ultima_actividad'] = time();

        if ($sesionActiva) {
            $id_usuario    = $_SESSION['id_usuario'];
            $minutosHoy    = SesionDAO::minutosHoy($id_usuario);
            $minutosSem    = SesionDAO::minutosSemana($id_usuario);
            $racha         = SesionDAO::rachaActual($id_usuario);
            $totalSesiones = SesionDAO::totalSesiones($id_usuario);
            include __DIR__ . '/../view/homeView.php';
        } else {
            header('Location: ../view/loginView.php');
        }
        exit();
    }

    //  REGISTRO 
    public static function registrar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $nombre_completo = trim($_POST['nombre_completo'] ?? '');
        $nombre_usuario  = trim($_POST['nombre_usuario']  ?? '');
        $email           = trim($_POST['email']           ?? '');
        $contrasena      = $_POST['contrasena']           ?? '';
        $confirmar       = $_POST['confirmar']            ?? '';
        $edad            = $_POST['edad']    !== '' ? $_POST['edad']    : null;
        $genero          = $_POST['genero']  !== '' ? $_POST['genero']  : null;
        $telefono        = $_POST['telefono']!== '' ? $_POST['telefono']: null;

        $v = new ValidadorPHP();
        $v->requerido($nombre_completo, 'Nombre completo')
          ->minLong($nombre_completo, 3, 'Nombre completo')
          ->maxLong($nombre_completo, 120, 'Nombre completo')
          ->requerido($nombre_usuario, 'Usuario')
          ->minLong($nombre_usuario, 3, 'Usuario')
          ->maxLong($nombre_usuario, 60, 'Usuario')
          ->alfanumerico($nombre_usuario, 'Usuario')
          ->requerido($email, 'Email')
          ->email($email, 'Email')
          ->requerido($contrasena, 'Contraseña')
          ->minLong($contrasena, 6, 'Contraseña');

        if ($contrasena !== $confirmar) {
            $_SESSION['errores_php'][] = 'Las contraseñas no coinciden.';
            header('Location: ../view/registroView.php');
            exit();
        }

        if ($edad !== null) {
            $v->rango($edad, 5, 120, 'Edad');
        }
        if ($genero !== null) {
            $v->enLista($genero, ['Hombre','Mujer','Otro'], 'Género');
        }

        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/registroView.php');
        }

        $contrasenaHash = hash('sha256', $contrasena);
        $fecha_alta     = date('Y-m-d H:i:s');
        $resultado = UsuarioDAO::crearUsuario($nombre_usuario, $contrasenaHash, $nombre_completo,
                                              $email, $edad, $genero, $telefono, 'usuario', $fecha_alta);
        if ($resultado) {
            $_SESSION['msg_ok'] = 'Cuenta creada correctamente. ¡Ya puedes iniciar sesión!';
            header('Location: ../view/loginView.php');
        } else {
            $_SESSION['error'] = 'registro';
            header('Location: ../view/registroView.php');
        }
        exit();
    }

    //  ACTUALIZAR PERFIL 
    public static function actualizarPerfil() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $nombre_completo = trim($_POST['nombre_completo'] ?? '');
        $nombre_usuario  = trim($_POST['nombre_usuario']  ?? '');
        $email           = trim($_POST['email']           ?? '');
        $edad            = $_POST['edad']    !== '' ? $_POST['edad']    : null;
        $genero          = $_POST['genero']  !== '' ? $_POST['genero']  : null;
        $telefono        = $_POST['telefono']!== '' ? $_POST['telefono']: null;
        $perfil          = $_SESSION['perfil_usuario'];
        $id_usuario      = $_SESSION['id_usuario'];

        $v = new ValidadorPHP();
        $v->requerido($nombre_completo, 'Nombre completo')
          ->minLong($nombre_completo, 3, 'Nombre completo')
          ->requerido($nombre_usuario, 'Usuario')
          ->minLong($nombre_usuario, 3, 'Usuario')
          ->requerido($email, 'Email')
          ->email($email, 'Email');

        if ($edad !== null) $v->rango($edad, 5, 120, 'Edad');
        if ($genero !== null) $v->enLista($genero, ['Hombre','Mujer','Otro'], 'Género');

        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/configuracionView.php');
        }

        $resultado = UsuarioDAO::actualizar($id_usuario, $nombre_completo, $nombre_usuario,
                                            $email, $edad, $genero, $telefono, $perfil);
        if ($resultado) {
            $_SESSION['nombre_completo'] = $nombre_completo;
            $_SESSION['nombre_usuario']  = $nombre_usuario;
            $_SESSION['msg_ok']          = 'Perfil actualizado correctamente.';
        } else {
            $_SESSION['error'] = 'perfil';
        }
        header('Location: ../view/configuracionView.php');
        exit();
    }

    //  CONTACTO 
    public static function contacto() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $nombre  = trim($_POST['nombre']  ?? '');
        $email   = trim($_POST['email']   ?? '');
        $asunto  = $_POST['asunto']  ?? '';
        $mensaje = trim($_POST['mensaje'] ?? '');

        $v = new ValidadorPHP();
        $v->requerido($nombre, 'Nombre')
          ->minLong($nombre, 2, 'Nombre')
          ->requerido($email, 'Email')
          ->email($email, 'Email')
          ->requerido($asunto, 'Asunto')
          ->enLista($asunto, ['soporte','sugerencia','colaboracion','otro'], 'Asunto')
          ->requerido($mensaje, 'Mensaje')
          ->minLong($mensaje, 10, 'Mensaje')
          ->maxLong($mensaje, 2000, 'Mensaje');

        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/contactoView.php');
        }

        $id_usuario = $_SESSION['id_usuario'] ?? null;
        ContactoDAO::crear($id_usuario, $nombre, $email, $asunto, $mensaje);

        // Si osTicket está configurado, además se crea una incidencia.
        // Si no está activo o falla, el mensaje queda guardado en la BD igualmente.
        $etiquetasAsunto = [
            'soporte'      => 'Soporte técnico',
            'sugerencia'   => 'Sugerencia',
            'colaboracion' => 'Colaboración',
            'otro'         => 'Otro',
        ];
        $asuntoTicket = ($etiquetasAsunto[$asunto] ?? 'Contacto') . ' - ' . $nombre;
        $ticket = OsTicketClient::crearTicket($nombre, $email, $asuntoTicket, $mensaje);
        if ($ticket !== false) {
            $_SESSION['msg_contacto_ticket'] = $ticket;
        }

        $_SESSION['msg_contacto_ok'] = true;
        header('Location: ../view/contactoView.php');
        exit();
    }

    //  LISTAR USUARIOS (admin) 
    public static function listarUsuarios() {
        $usuarios = UsuarioDAO::listarTodos();
        include __DIR__ . '/../view/admin/usuariosView.php';
        exit();
    }

    //  CREAR USUARIO (admin) 
    public static function crearUsuarioAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $nombre_completo = trim($_POST['nombre_completo'] ?? '');
        $nombre_usuario  = trim($_POST['nombre_usuario']  ?? '');
        $email           = trim($_POST['email']           ?? '');
        $contrasena      = $_POST['contrasena'] ?? '';
        $perfil          = $_POST['perfil']     ?? '';

        $v = new ValidadorPHP();
        $v->requerido($nombre_completo, 'Nombre completo')
          ->minLong($nombre_completo, 3, 'Nombre completo')
          ->requerido($nombre_usuario, 'Usuario')
          ->minLong($nombre_usuario, 3, 'Usuario')
          ->alfanumerico($nombre_usuario, 'Usuario')
          ->requerido($email, 'Email')
          ->email($email, 'Email')
          ->requerido($contrasena, 'Contraseña')
          ->minLong($contrasena, 6, 'Contraseña')
          ->requerido($perfil, 'Perfil')
          ->enLista($perfil, ['usuario','admin'], 'Perfil');

        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/admin/usuarioFormView.php');
        }

        $hash       = hash('sha256', $contrasena);
        $fecha_alta = date('Y-m-d H:i:s');
        $resultado  = UsuarioDAO::crearUsuario($nombre_usuario, $hash, $nombre_completo,
                                               $email, null, null, null, $perfil, $fecha_alta);
        if ($resultado) {
            $_SESSION['msg_ok'] = 'Usuario creado correctamente.';
        }
        self::listarUsuarios();
    }

    //  EDITAR USUARIO (admin) 
    public static function editarUsuario($id_usuario) {
        $usuario = UsuarioDAO::obtenerPorId($id_usuario);
        include __DIR__ . '/../view/admin/usuarioFormView.php';
        exit();
    }

    //  ACTUALIZAR USUARIO (admin) 
    public static function actualizarUsuario() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id_usuario      = (int)($_POST['id_usuario']      ?? 0);
        $nombre_completo = trim($_POST['nombre_completo']  ?? '');
        $nombre_usuario  = trim($_POST['nombre_usuario']   ?? '');
        $email           = trim($_POST['email']            ?? '');
        $edad            = $_POST['edad']   !== '' ? $_POST['edad']   : null;
        $genero          = $_POST['genero'] !== '' ? $_POST['genero'] : null;
        $telefono        = $_POST['telefono']!== '' ? $_POST['telefono']: null;
        $perfil          = $_POST['perfil'] ?? '';

        $v = new ValidadorPHP();
        $v->min($id_usuario, 1, 'ID de usuario')
          ->requerido($nombre_completo, 'Nombre completo')
          ->minLong($nombre_completo, 3, 'Nombre completo')
          ->requerido($nombre_usuario, 'Usuario')
          ->minLong($nombre_usuario, 3, 'Usuario')
          ->requerido($email, 'Email')
          ->email($email, 'Email')
          ->requerido($perfil, 'Perfil')
          ->enLista($perfil, ['usuario','admin'], 'Perfil');

        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/admin/usuariosView.php');
        }

        UsuarioDAO::actualizar($id_usuario, $nombre_completo, $nombre_usuario,
                               $email, $edad, $genero, $telefono, $perfil);
        $_SESSION['msg_ok'] = 'Usuario actualizado.';
        self::listarUsuarios();
    }

    //  LISTAR MENSAJES DE CONTACTO (admin) 
    public static function listarMensajesContacto() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['perfil_usuario']) || $_SESSION['perfil_usuario'] !== 'admin') {
            header('Location: ../view/loginView.php');
            exit();
        }
        $filtro_asunto   = $_GET['filtro_asunto']   ?? $_POST['filtro_asunto']   ?? null;
        $filtro_usuario  = $_GET['filtro_usuario']  ?? $_POST['filtro_usuario']  ?? null;
        $filtro_desde    = $_GET['filtro_desde']    ?? $_POST['filtro_desde']    ?? null;
        $filtro_hasta    = $_GET['filtro_hasta']    ?? $_POST['filtro_hasta']    ?? null;
        $mensajes = ContactoDAO::listar($filtro_asunto, $filtro_usuario, $filtro_desde, $filtro_hasta);
        $usuarios = UsuarioDAO::listarTodos();
        include __DIR__ . '/../view/admin/mensajesContactoView.php';
        exit();
    }

    //  ELIMINAR MENSAJE DE CONTACTO (admin) 
    public static function eliminarMensajeContacto($id_mensaje) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['perfil_usuario']) || $_SESSION['perfil_usuario'] !== 'admin') {
            header('Location: ../view/loginView.php');
            exit();
        }
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_mensaje, 'ID de mensaje');
        if ($v->hayErrores()) {
            header('Location: ../controller/UsuarioController.php?action=listarMensajesContacto');
            exit();
        }
        ContactoDAO::eliminar((int) $id_mensaje);
        $_SESSION['msg_ok'] = 'Mensaje eliminado.';
        header('Location: ../controller/UsuarioController.php?action=listarMensajesContacto');
        exit();
    }

    //  ELIMINAR USUARIO (admin) 
    public static function eliminarUsuario($id_usuario) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $v = new ValidadorPHP();
        $v->enteroPositivo($id_usuario, 'ID de usuario');
        if ($v->hayErrores()) {
            $v->redirigirConErrores('../view/admin/usuariosView.php');
        }
        UsuarioDAO::eliminar((int)$id_usuario);
        $_SESSION['msg_ok'] = 'Usuario eliminado.';
        self::listarUsuarios();
    }
}

//  ROUTER 
$accion = $_POST['action'] ?? $_GET['action'] ?? null;
switch ($accion) {
    case 'login':              UsuarioController::login();              break;
    case 'logout':             UsuarioController::logout();             break;
    case 'registrar':          UsuarioController::registrar();          break;
    case 'actualizarPerfil':   UsuarioController::actualizarPerfil();   break;
    case 'contacto':           UsuarioController::contacto();           break;
    case 'listarUsuarios':     UsuarioController::listarUsuarios();     break;
    case 'listarMensajesContacto':  UsuarioController::listarMensajesContacto();  break;
    case 'eliminarMensajeContacto': UsuarioController::eliminarMensajeContacto($_POST['id_mensaje'] ?? $_GET['id_mensaje'] ?? 0); break;
    case 'crearUsuarioAdmin':  UsuarioController::crearUsuarioAdmin();  break;
    case 'editarUsuario':      UsuarioController::editarUsuario($_POST['id_usuario'] ?? 0); break;
    case 'actualizarUsuario':  UsuarioController::actualizarUsuario();  break;
    case 'eliminarUsuario':    UsuarioController::eliminarUsuario($_POST['id_usuario'] ?? 0); break;
    default:                   UsuarioController::verificarSesion();    break;
}
?>
