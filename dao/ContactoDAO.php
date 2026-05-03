<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/MensajeContactoModel.php');

class ContactoDAO {

    private static function filaAModelo($fila) {
        $m = new MensajeContactoModel();
        $m->setIdMensaje($fila['id_mensaje']);
        $m->setIdUsuario($fila['id_usuario'] ?? null);
        if (isset($fila['nombre_usuario'])) {
            $m->setNombreUsuario($fila['nombre_usuario']);
        }
        $m->setNombre($fila['nombre']);
        $m->setEmail($fila['email']);
        $m->setAsunto($fila['asunto']);
        $m->setMensaje($fila['mensaje']);
        $m->setFechaEnvio($fila['fecha_envio']);
        return $m;
    }

    /** Guardar un mensaje de contacto (id_usuario puede ser null si no está logueado) */
    public static function crear($id_usuario, $nombre, $email, $asunto, $mensaje) {
        $consulta = "INSERT INTO mensajes_contacto (id_usuario, nombre, email, asunto, mensaje)
                     VALUES (?, ?, ?, ?, ?)";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $nombre, $email, $asunto, $mensaje);
    }

    /** Listar todos los mensajes, opcionalmente filtrados */
    public static function listar($filtro_asunto = null, $filtro_id_usuario = null, $filtro_desde = null, $filtro_hasta = null) {
        $consulta = "SELECT m.*, u.nombre_usuario
                     FROM mensajes_contacto m
                     LEFT JOIN usuarios u ON m.id_usuario = u.id_usuario
                     WHERE 1=1";
        $params = [];
        $tipos = "";

        if ($filtro_asunto !== null && $filtro_asunto !== '') {
            $consulta .= " AND m.asunto = ?";
            $params[] = $filtro_asunto;
            $tipos .= "s";
        }
        if ($filtro_id_usuario !== null && $filtro_id_usuario !== '') {
            $consulta .= " AND m.id_usuario = ?";
            $params[] = (int) $filtro_id_usuario;
            $tipos .= "i";
        }
        if ($filtro_desde !== null && $filtro_desde !== '') {
            $consulta .= " AND DATE(m.fecha_envio) >= ?";
            $params[] = $filtro_desde;
            $tipos .= "s";
        }
        if ($filtro_hasta !== null && $filtro_hasta !== '') {
            $consulta .= " AND DATE(m.fecha_envio) <= ?";
            $params[] = $filtro_hasta;
            $tipos .= "s";
        }

        $consulta .= " ORDER BY m.fecha_envio DESC";

        if (empty($params)) {
            $resultado = SDPBD::consultaLectura($consulta);
        } else {
            $resultado = SDPBD::consultaLectura($consulta, ...$params);
        }

        $mensajes = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $mensajes[] = self::filaAModelo($fila);
            }
        }
        return $mensajes;
    }

    /** Obtener un mensaje por id */
    public static function obtenerPorId($id_mensaje) {
        $consulta  = "SELECT m.*, u.nombre_usuario
                      FROM mensajes_contacto m
                      LEFT JOIN usuarios u ON m.id_usuario = u.id_usuario
                      WHERE m.id_mensaje = ?";
        $resultado = SDPBD::consultaLectura($consulta, $id_mensaje);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    /** Eliminar mensaje (admin) */
    public static function eliminar($id_mensaje) {
        $consulta = "DELETE FROM mensajes_contacto WHERE id_mensaje = ?";
        return SDPBD::consultaInsercion($consulta, $id_mensaje);
    }
}
?>
