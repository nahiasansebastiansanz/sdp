<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/CategoriaModel.php');

class CategoriaDAO {

    private static function filaAModelo($fila) {
        $c = new CategoriaModel();
        $c->setIdCategoria($fila['id_categoria']);
        $c->setNombre($fila['nombre']);
        $c->setDescripcion($fila['descripcion'] ?? null);
        $c->setIcono($fila['icono'] ?? '🏷️');
        return $c;
    }

    public static function crear($nombre, $descripcion, $icono) {
        $consulta = "INSERT INTO categorias (nombre, descripcion, icono) VALUES (?, ?, ?)";
        return SDPBD::consultaInsercion($consulta, $nombre, $descripcion, $icono);
    }

    public static function listarTodas() {
        $consulta  = "SELECT c.*, COUNT(m.id_meditacion) AS num_meditaciones
                      FROM categorias c
                      LEFT JOIN meditaciones m ON c.id_categoria = m.id_categoria
                      GROUP BY c.id_categoria
                      ORDER BY c.nombre";
        $resultado = SDPBD::consultaLectura($consulta);
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $obj = self::filaAModelo($fila);
                $lista[] = ['modelo' => $obj, 'num_meditaciones' => $fila['num_meditaciones']];
            }
        }
        return $lista;
    }

    public static function obtenerPorId($id_categoria) {
        $consulta  = "SELECT * FROM categorias WHERE id_categoria = ?";
        $resultado = SDPBD::consultaLectura($consulta, $id_categoria);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    public static function actualizar($id_categoria, $nombre, $descripcion, $icono) {
        $consulta = "UPDATE categorias SET nombre=?, descripcion=?, icono=? WHERE id_categoria=?";
        return SDPBD::consultaInsercion($consulta, $nombre, $descripcion, $icono, $id_categoria);
    }

    public static function eliminar($id_categoria) {
        $consulta = "DELETE FROM categorias WHERE id_categoria = ?";
        return SDPBD::consultaInsercion($consulta, $id_categoria);
    }
}
?>
