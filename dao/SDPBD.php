<?php

class SDPBD {

    private static $conexion = null;

    // Conectar a la base de datos (singleton)
    private static function conexionBD() {
        $config = parse_ini_file(__DIR__ . "/../config/conf.ini");
        if (self::$conexion === null) {
            self::$conexion = new mysqli(
                $config['server'],
                $config['user'],
                $config['pasw'],
                $config['bd']
            );
        }
        if (self::$conexion->connect_error) {
            die("Error en la conexión: " . self::$conexion->connect_error);
        }
        self::$conexion->set_charset("utf8mb4");
        return self::$conexion;
    }

    // Preparar statement con parámetros
    public static function preparar($conexion, $consulta, ...$parametros) {
        $preparacion = $conexion->prepare($consulta);
        if ($parametros) {
            $tipos = "";
            foreach ($parametros as $parametro) {
                $tipos .= is_int($parametro) ? "i" : "s";
            }
            $preparacion->bind_param($tipos, ...$parametros);
        }
        return $preparacion;
    }

    // INSERT / UPDATE / DELETE
    public static function consultaInsercion($consulta, ...$parametros) {
        $conexion  = self::conexionBD();
        $preparacion = self::preparar($conexion, $consulta, ...$parametros);
        if ($preparacion->execute()) {
            $id = $conexion->insert_id;
            return $id ?: true;
        }
        return false;
    }

    // SELECT - devuelve array asociativo o null
    public static function consultaLectura($consulta, ...$parametros) {
        $conexion    = self::conexionBD();
        $preparacion = self::preparar($conexion, $consulta, ...$parametros);
        $preparacion->execute();
        $resultado = $preparacion->get_result();
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }
        return null;
    }

    // Cerrar conexión
    public static function cerrarConexion() {
        if (self::$conexion !== null) {
            self::$conexion->close();
            self::$conexion = null;
        }
    }
}
?>
