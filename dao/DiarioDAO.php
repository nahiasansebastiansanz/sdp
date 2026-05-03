<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/DiarioModel.php');

class DiarioDAO {

    private static function filaAModelo($fila) {
        $d = new DiarioModel();
        $d->setIdEntrada($fila['id_entrada']);
        $d->setIdUsuario($fila['id_usuario']);
        $d->setTitulo($fila['titulo']);
        $d->setContenido($fila['contenido']);
        $d->setHumor($fila['humor']);
        $d->setFechaEntrada($fila['fecha_entrada']);
        return $d;
    }

    public static function crear($id_usuario, $titulo, $contenido, $humor) {
        $consulta = "INSERT INTO diario (id_usuario, titulo, contenido, humor, fecha_entrada) VALUES (?, ?, ?, ?, NOW())";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $titulo, $contenido, $humor);
    }

    public static function listarPorUsuario($id_usuario) {
        $consulta  = "SELECT * FROM diario WHERE id_usuario = ? ORDER BY fecha_entrada DESC";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = self::filaAModelo($fila);
            }
        }
        return $lista;
    }

    public static function obtenerPorId($id_entrada) {
        $resultado = SDPBD::consultaLectura("SELECT * FROM diario WHERE id_entrada = ?", $id_entrada);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    public static function actualizar($id_entrada, $titulo, $contenido, $humor) {
        $consulta = "UPDATE diario SET titulo=?, contenido=?, humor=? WHERE id_entrada=?";
        return SDPBD::consultaInsercion($consulta, $titulo, $contenido, $humor, $id_entrada);
    }

    public static function eliminar($id_entrada) {
        $consulta = "DELETE FROM diario WHERE id_entrada = ?";
        return SDPBD::consultaInsercion($consulta, $id_entrada);
    }
}
?>
