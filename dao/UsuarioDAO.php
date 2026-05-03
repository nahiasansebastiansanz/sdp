<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/UsuarioModel.php');

class UsuarioDAO {

    // Mapea una fila de BD a un objeto UsuarioModel
    private static function filaAModelo($fila) {
        $u = new UsuarioModel();
        $u->setIdUsuario($fila['id_usuario']);
        $u->setNombreUsuario($fila['nombre_usuario']);
        $u->setContrasenaHash($fila['contrasena_hash']);
        $u->setNombreCompleto($fila['nombre_completo']);
        $u->setEmail($fila['email']);
        $u->setEdad($fila['edad'] ?? null);
        $u->setGenero($fila['genero'] ?? null);
        $u->setTelefono($fila['telefono'] ?? null);
        $u->setPerfil($fila['perfil']);
        $u->setFechaAlta($fila['fecha_alta']);
        return $u;
    }

    // Crear usuario
    public static function crearUsuario($nombre_usuario, $contrasena_hash, $nombre_completo, $email, $edad, $genero, $telefono, $perfil, $fecha_alta) {
        $consulta = "INSERT INTO usuarios (nombre_usuario, contrasena_hash, nombre_completo, email, edad, genero, telefono, perfil, fecha_alta)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return SDPBD::consultaInsercion($consulta, $nombre_usuario, $contrasena_hash, $nombre_completo, $email, $edad, $genero, $telefono, $perfil, $fecha_alta);
    }

    // Obtener por nombre_usuario
    public static function obtenerPorNombreUsuario($nombre_usuario) {
        $consulta  = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $resultado = SDPBD::consultaLectura($consulta, $nombre_usuario);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    // Obtener por id
    public static function obtenerPorId($id_usuario) {
        $consulta  = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    // Listar todos
    public static function listarTodos() {
        $consulta  = "SELECT * FROM usuarios ORDER BY fecha_alta DESC";
        $resultado = SDPBD::consultaLectura($consulta);
        $usuarios  = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $usuarios[] = self::filaAModelo($fila);
            }
        }
        return $usuarios;
    }

    // Actualizar
    public static function actualizar($id_usuario, $nombre_completo, $nombre_usuario, $email, $edad, $genero, $telefono, $perfil) {
        $consulta = "UPDATE usuarios SET nombre_completo=?, nombre_usuario=?, email=?, edad=?, genero=?, telefono=?, perfil=? WHERE id_usuario=?";
        return SDPBD::consultaInsercion($consulta, $nombre_completo, $nombre_usuario, $email, $edad, $genero, $telefono, $perfil, $id_usuario);
    }

    // Eliminar
    public static function eliminar($id_usuario) {
        $consulta = "DELETE FROM usuarios WHERE id_usuario = ?";
        return SDPBD::consultaInsercion($consulta, $id_usuario);
    }
}
?>
