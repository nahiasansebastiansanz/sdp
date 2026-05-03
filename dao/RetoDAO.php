<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/RetoModel.php');

class RetoDAO {

    private static function filaAModelo($fila) {
        $r = new RetoModel();
        $r->setIdReto($fila['id_reto']);
        $r->setTitulo($fila['titulo']);
        $r->setDescripcion($fila['descripcion'] ?? null);
        $r->setTipo($fila['tipo']);
        $r->setObjetivoValor($fila['objetivo_valor']);
        $r->setDuracionDias($fila['duracion_dias']);
        $r->setActivo($fila['activo']);
        return $r;
    }

    public static function crear($titulo, $descripcion, $tipo, $objetivo_valor, $duracion_dias) {
        $consulta = "INSERT INTO retos (titulo, descripcion, tipo, objetivo_valor, duracion_dias, activo) VALUES (?, ?, ?, ?, ?, 1)";
        return SDPBD::consultaInsercion($consulta, $titulo, $descripcion, $tipo, $objetivo_valor, $duracion_dias);
    }

    public static function listarActivos() {
        $resultado = SDPBD::consultaLectura("SELECT * FROM retos WHERE activo = 1 ORDER BY titulo");
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = self::filaAModelo($fila);
            }
        }
        return $lista;
    }

    public static function listarTodos() {
        $consulta  = "SELECT r.*, COUNT(ur.id_usuario) AS num_participantes
                      FROM retos r
                      LEFT JOIN usuario_reto ur ON r.id_reto = ur.id_reto
                      GROUP BY r.id_reto
                      ORDER BY r.titulo";
        $resultado = SDPBD::consultaLectura($consulta);
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = ['modelo' => self::filaAModelo($fila), 'num_participantes' => $fila['num_participantes']];
            }
        }
        return $lista;
    }

    public static function obtenerPorId($id_reto) {
        $resultado = SDPBD::consultaLectura("SELECT * FROM retos WHERE id_reto = ?", $id_reto);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    // Retos del usuario con progreso
    public static function retosUsuario($id_usuario) {
        $consulta  = "SELECT r.*, ur.progreso, ur.completado, ur.fecha_inicio
                      FROM usuario_reto ur
                      JOIN retos r ON ur.id_reto = r.id_reto
                      WHERE ur.id_usuario = ?
                      ORDER BY ur.fecha_inicio DESC";
        return SDPBD::consultaLectura($consulta, $id_usuario) ?? [];
    }

    // Aceptar reto
    public static function aceptar($id_usuario, $id_reto) {
        $consulta = "INSERT IGNORE INTO usuario_reto (id_usuario, id_reto, progreso, completado, fecha_inicio) VALUES (?, ?, 0, 0, NOW())";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $id_reto);
    }

    // Actualizar progreso
    public static function actualizarProgreso($id_usuario, $id_reto, $progreso) {
        $consulta = "UPDATE usuario_reto SET progreso=? WHERE id_usuario=? AND id_reto=?";
        return SDPBD::consultaInsercion($consulta, $progreso, $id_usuario, $id_reto);
    }

    // Marcar reto como completado
    public static function marcarCompletado($id_usuario, $id_reto) {
        $consulta = "UPDATE usuario_reto SET completado=1 WHERE id_usuario=? AND id_reto=?";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $id_reto);
    }

    public static function actualizar($id_reto, $titulo, $descripcion, $tipo, $objetivo_valor, $duracion_dias, $activo) {
        $consulta = "UPDATE retos SET titulo=?, descripcion=?, tipo=?, objetivo_valor=?, duracion_dias=?, activo=? WHERE id_reto=?";
        return SDPBD::consultaInsercion($consulta, $titulo, $descripcion, $tipo, $objetivo_valor, $duracion_dias, $activo, $id_reto);
    }

    public static function eliminar($id_reto) {
        $consulta = "DELETE FROM retos WHERE id_reto = ?";
        return SDPBD::consultaInsercion($consulta, $id_reto);
    }
}
?>
