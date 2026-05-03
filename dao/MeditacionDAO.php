<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/MeditacionModel.php');

class MeditacionDAO {

    private static function filaAModelo($fila) {
        $m = new MeditacionModel();
        $m->setIdMeditacion($fila['id_meditacion']);
        $m->setTitulo($fila['titulo']);
        $m->setDescripcion($fila['descripcion'] ?? null);
        $m->setIdCategoria($fila['id_categoria']);
        $m->setNombreCategoria($fila['nombre_categoria'] ?? null);
        $m->setNivel($fila['nivel']);
        $m->setDuracionMin($fila['duracion_min']);
        $m->setIcono($fila['icono'] ?? '🧘');
        $m->setArchivoAudio($fila['archivo_audio'] ?? null);
        $m->setInstrucciones($fila['instrucciones'] ?? null);
        return $m;
    }

    // Crear
    public static function crear($titulo, $descripcion, $id_categoria, $nivel, $duracion_min, $icono, $archivo_audio, $instrucciones) {
        $consulta = "INSERT INTO meditaciones (titulo, descripcion, id_categoria, nivel, duracion_min, icono, archivo_audio, instrucciones)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return SDPBD::consultaInsercion($consulta, $titulo, $descripcion, $id_categoria, $nivel, $duracion_min, $icono, $archivo_audio, $instrucciones);
    }

    // Listar todas con nombre de categoría
    public static function listarTodas() {
        $consulta = "SELECT m.*, c.nombre AS nombre_categoria
                     FROM meditaciones m
                     LEFT JOIN categorias c ON m.id_categoria = c.id_categoria
                     ORDER BY m.titulo";
        $resultado = SDPBD::consultaLectura($consulta);
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = self::filaAModelo($fila);
            }
        }
        return $lista;
    }

    // Listar por categoría
    public static function listarPorCategoria($id_categoria) {
        $consulta  = "SELECT m.*, c.nombre AS nombre_categoria
                      FROM meditaciones m
                      LEFT JOIN categorias c ON m.id_categoria = c.id_categoria
                      WHERE m.id_categoria = ?
                      ORDER BY m.titulo";
        $resultado = SDPBD::consultaLectura($consulta, $id_categoria);
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $lista[] = self::filaAModelo($fila);
            }
        }
        return $lista;
    }

    // Obtener por id
    public static function obtenerPorId($id_meditacion) {
        $consulta  = "SELECT m.*, c.nombre AS nombre_categoria
                      FROM meditaciones m
                      LEFT JOIN categorias c ON m.id_categoria = c.id_categoria
                      WHERE m.id_meditacion = ?";
        $resultado = SDPBD::consultaLectura($consulta, $id_meditacion);
        if ($resultado && count($resultado) > 0) {
            return self::filaAModelo($resultado[0]);
        }
        return null;
    }

    // Actualizar
    public static function actualizar($id_meditacion, $titulo, $descripcion, $id_categoria, $nivel, $duracion_min, $icono, $instrucciones) {
        $consulta = "UPDATE meditaciones SET titulo=?, descripcion=?, id_categoria=?, nivel=?, duracion_min=?, icono=?, instrucciones=? WHERE id_meditacion=?";
        return SDPBD::consultaInsercion($consulta, $titulo, $descripcion, $id_categoria, $nivel, $duracion_min, $icono, $instrucciones, $id_meditacion);
    }

    // Eliminar
    public static function eliminar($id_meditacion) {
        $consulta = "DELETE FROM meditaciones WHERE id_meditacion = ?";
        return SDPBD::consultaInsercion($consulta, $id_meditacion);
    }

    // Listar favoritos de un usuario
    public static function listarFavoritos($id_usuario) {
        $consulta  = "SELECT m.*, c.nombre AS nombre_categoria, f.fecha_guardado
                      FROM favoritos f
                      JOIN meditaciones m ON f.id_meditacion = m.id_meditacion
                      LEFT JOIN categorias c ON m.id_categoria = c.id_categoria
                      WHERE f.id_usuario = ?
                      ORDER BY f.fecha_guardado DESC";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        $lista = [];
        if ($resultado) {
            foreach ($resultado as $fila) {
                $obj = self::filaAModelo($fila);
                $lista[] = $obj;
            }
        }
        return $lista;
    }

    // Añadir favorito
    public static function añadirFavorito($id_usuario, $id_meditacion) {
        $consulta = "INSERT IGNORE INTO favoritos (id_usuario, id_meditacion, fecha_guardado) VALUES (?, ?, NOW())";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $id_meditacion);
    }

    // Quitar favorito
    public static function quitarFavorito($id_usuario, $id_meditacion) {
        $consulta = "DELETE FROM favoritos WHERE id_usuario = ? AND id_meditacion = ?";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $id_meditacion);
    }
}
?>
