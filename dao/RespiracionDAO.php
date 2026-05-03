<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/RespiracionModel.php');

class RespiracionDAO {

    private static function filaAModelo($fila) {
        $r = new RespiracionModel();
        $r->setIdRespiracion($fila['id_respiracion']);
        $r->setNombre($fila['nombre']);
        $r->setDescripcion($fila['descripcion'] ?? null);
        $r->setInhalaSeg($fila['inhala_seg']);
        $r->setRetieneSeg($fila['retiene_seg']);
        $r->setExhalaSeg($fila['exhala_seg']);
        $r->setRetiene2Seg($fila['retiene2_seg']);
        $r->setCiclos($fila['ciclos']);
        return $r;
    }

    public static function crear($nombre, $descripcion, $inhala_seg, $retiene_seg, $exhala_seg, $retiene2_seg, $ciclos) {
        $consulta = "INSERT INTO respiraciones (nombre, descripcion, inhala_seg, retiene_seg, exhala_seg, retiene2_seg, ciclos)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        return SDPBD::consultaInsercion($consulta, $nombre, $descripcion, $inhala_seg, $retiene_seg, $exhala_seg, $retiene2_seg, $ciclos);
    }

    public static function listarTodas() {
        $resultado = SDPBD::consultaLectura("SELECT * FROM respiraciones ORDER BY nombre");
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = self::filaAModelo($fila);
            }
        }
        return $lista;
    }

    public static function obtenerPorId($id_respiracion) {
        $resultado = SDPBD::consultaLectura("SELECT * FROM respiraciones WHERE id_respiracion = ?", $id_respiracion);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    public static function actualizar($id_respiracion, $nombre, $descripcion, $inhala_seg, $retiene_seg, $exhala_seg, $retiene2_seg, $ciclos) {
        $consulta = "UPDATE respiraciones SET nombre=?, descripcion=?, inhala_seg=?, retiene_seg=?, exhala_seg=?, retiene2_seg=?, ciclos=? WHERE id_respiracion=?";
        return SDPBD::consultaInsercion($consulta, $nombre, $descripcion, $inhala_seg, $retiene_seg, $exhala_seg, $retiene2_seg, $ciclos, $id_respiracion);
    }

    public static function eliminar($id_respiracion) {
        $consulta = "DELETE FROM respiraciones WHERE id_respiracion = ?";
        return SDPBD::consultaInsercion($consulta, $id_respiracion);
    }
}
?>
