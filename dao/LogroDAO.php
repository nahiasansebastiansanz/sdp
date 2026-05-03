<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/LogroModel.php');

class LogroDAO {

    private static function filaAModelo($fila) {
        $l = new LogroModel();
        $l->setIdLogro($fila['id_logro']);
        $l->setTitulo($fila['titulo']);
        $l->setDescripcion($fila['descripcion'] ?? null);
        $l->setIcono($fila['icono'] ?? '🏅');
        $l->setCondicionTipo($fila['condicion_tipo']);
        $l->setCondicionValor($fila['condicion_valor']);
        return $l;
    }

    public static function crear($titulo, $descripcion, $icono, $condicion_tipo, $condicion_valor) {
        $consulta = "INSERT INTO logros (titulo, descripcion, icono, condicion_tipo, condicion_valor) VALUES (?, ?, ?, ?, ?)";
        return SDPBD::consultaInsercion($consulta, $titulo, $descripcion, $icono, $condicion_tipo, $condicion_valor);
    }

    public static function listarTodos() {
        $resultado = SDPBD::consultaLectura("SELECT * FROM logros ORDER BY condicion_valor ASC");
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = self::filaAModelo($fila);
            }
        }
        return $lista;
    }

    public static function obtenerPorId($id_logro) {
        $resultado = SDPBD::consultaLectura("SELECT * FROM logros WHERE id_logro = ?", $id_logro);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    // Logros de un usuario (obtenidos)
    public static function logrosObtenidos($id_usuario) {
        $consulta  = "SELECT l.*, ul.fecha_obtencion
                      FROM usuario_logro ul
                      JOIN logros l ON ul.id_logro = l.id_logro
                      WHERE ul.id_usuario = ?
                      ORDER BY ul.fecha_obtencion DESC";
        return SDPBD::consultaLectura($consulta, $id_usuario) ?? [];
    }

    // Conceder logro
    public static function conceder($id_usuario, $id_logro) {
        $consulta = "INSERT IGNORE INTO usuario_logro (id_usuario, id_logro, fecha_obtencion) VALUES (?, ?, NOW())";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $id_logro);
    }

    public static function actualizar($id_logro, $titulo, $descripcion, $icono, $condicion_tipo, $condicion_valor) {
        $consulta = "UPDATE logros SET titulo=?, descripcion=?, icono=?, condicion_tipo=?, condicion_valor=? WHERE id_logro=?";
        return SDPBD::consultaInsercion($consulta, $titulo, $descripcion, $icono, $condicion_tipo, $condicion_valor, $id_logro);
    }

    public static function eliminar($id_logro) {
        $consulta = "DELETE FROM logros WHERE id_logro = ?";
        return SDPBD::consultaInsercion($consulta, $id_logro);
    }
}
?>
