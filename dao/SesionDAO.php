<?php
require_once(__DIR__ . '/../dao/SDPBD.php');
require_once(__DIR__ . '/../model/SesionModel.php');

class SesionDAO {

    private static function filaAModelo($fila) {
        $s = new SesionModel();
        $s->setIdSesion($fila['id_sesion']);
        $s->setIdUsuario($fila['id_usuario']);
        $s->setTipo($fila['tipo']);
        $s->setDuracionMin($fila['duracion_min']);
        $s->setIdMeditacion($fila['id_meditacion'] ?? null);
        $s->setConGong($fila['con_gong']);
        $s->setFechaSesion($fila['fecha_sesion']);
        return $s;
    }

    // Registrar sesión completada
    public static function registrar($id_usuario, $tipo, $duracion_min, $id_meditacion, $con_gong) {
        $consulta = "INSERT INTO sesiones (id_usuario, tipo, duracion_min, id_meditacion, con_gong, fecha_sesion)
                     VALUES (?, ?, ?, ?, ?, NOW())";
        return SDPBD::consultaInsercion($consulta, $id_usuario, $tipo, $duracion_min, $id_meditacion, $con_gong);
    }

    // Historial de un usuario
    public static function historialPorUsuario($id_usuario) {
        $consulta  = "SELECT s.*, m.titulo AS titulo_meditacion
                      FROM sesiones s
                      LEFT JOIN meditaciones m ON s.id_meditacion = m.id_meditacion
                      WHERE s.id_usuario = ?
                      ORDER BY s.fecha_sesion DESC";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        return $resultado ?? [];
    }

    // Total minutos de hoy
    public static function minutosHoy($id_usuario) {
        $consulta  = "SELECT COALESCE(SUM(duracion_min), 0) AS total FROM sesiones WHERE id_usuario = ? AND DATE(fecha_sesion) = CURDATE()";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Total minutos esta semana
    public static function minutosSemana($id_usuario) {
        $consulta  = "SELECT COALESCE(SUM(duracion_min), 0) AS total FROM sesiones WHERE id_usuario = ? AND YEARWEEK(fecha_sesion, 1) = YEARWEEK(NOW(), 1)";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Total minutos este mes
    public static function minutosMes($id_usuario) {
        $consulta  = "SELECT COALESCE(SUM(duracion_min), 0) AS total FROM sesiones WHERE id_usuario = ? AND MONTH(fecha_sesion) = MONTH(NOW()) AND YEAR(fecha_sesion) = YEAR(NOW())";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Total minutos general
    public static function minutosTotal($id_usuario) {
        $consulta  = "SELECT COALESCE(SUM(duracion_min), 0) AS total FROM sesiones WHERE id_usuario = ?";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Racha actual (días consecutivos)
    public static function rachaActual($id_usuario) {
        $consulta  = "SELECT DATE(fecha_sesion) AS dia FROM sesiones WHERE id_usuario = ? GROUP BY DATE(fecha_sesion) ORDER BY dia DESC";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        if (!$resultado) return 0;
        $racha = 0;
        $hoy   = new DateTime('today');
        foreach ($resultado as $fila) {
            $dia = new DateTime($fila['dia']);
            $diff = $hoy->diff($dia)->days;
            if ($diff == $racha) {
                $racha++;
                $hoy = clone $dia;
            } else {
                break;
            }
        }
        return $racha;
    }

    // Total sesiones
    public static function totalSesiones($id_usuario) {
        $consulta  = "SELECT COUNT(*) AS total FROM sesiones WHERE id_usuario = ?";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Minutos por día últimos 7 días
    public static function minutosPorDia7($id_usuario) {
        $consulta  = "SELECT DATE(fecha_sesion) AS dia, SUM(duracion_min) AS minutos
                      FROM sesiones WHERE id_usuario = ? AND fecha_sesion >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                      GROUP BY DATE(fecha_sesion) ORDER BY dia ASC";
        return SDPBD::consultaLectura($consulta, $id_usuario) ?? [];
    }

    // Distribución por tipo
    public static function distribucionTipos($id_usuario) {
        $consulta  = "SELECT tipo, COUNT(*) AS total FROM sesiones WHERE id_usuario = ? GROUP BY tipo";
        return SDPBD::consultaLectura($consulta, $id_usuario) ?? [];
    }

    // Suma de minutos meditados en [fecha_inicio, fecha_inicio + dias)
    public static function minutosEnRango($id_usuario, $fecha_inicio, $dias) {
        $consulta  = "SELECT COALESCE(SUM(duracion_min), 0) AS total
                      FROM sesiones
                      WHERE id_usuario = ?
                        AND fecha_sesion >= ?
                        AND fecha_sesion < DATE_ADD(?, INTERVAL ? DAY)";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario, $fecha_inicio, $fecha_inicio, $dias);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Número de sesiones en [fecha_inicio, fecha_inicio + dias)
    public static function sesionesEnRango($id_usuario, $fecha_inicio, $dias) {
        $consulta  = "SELECT COUNT(*) AS total
                      FROM sesiones
                      WHERE id_usuario = ?
                        AND fecha_sesion >= ?
                        AND fecha_sesion < DATE_ADD(?, INTERVAL ? DAY)";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario, $fecha_inicio, $fecha_inicio, $dias);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Tipos distintos de meditación probados en [fecha_inicio, fecha_inicio + dias)
    public static function tiposDistintosEnRango($id_usuario, $fecha_inicio, $dias) {
        $consulta  = "SELECT COUNT(DISTINCT tipo) AS total
                      FROM sesiones
                      WHERE id_usuario = ?
                        AND fecha_sesion >= ?
                        AND fecha_sesion < DATE_ADD(?, INTERVAL ? DAY)";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario, $fecha_inicio, $fecha_inicio, $dias);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Días distintos con al menos una sesión en [fecha_inicio, fecha_inicio + dias)
    public static function diasDistintosEnRango($id_usuario, $fecha_inicio, $dias) {
        $consulta  = "SELECT COUNT(DISTINCT DATE(fecha_sesion)) AS total
                      FROM sesiones
                      WHERE id_usuario = ?
                        AND fecha_sesion >= ?
                        AND fecha_sesion < DATE_ADD(?, INTERVAL ? DAY)";
        $resultado = SDPBD::consultaLectura($consulta, $id_usuario, $fecha_inicio, $fecha_inicio, $dias);
        return $resultado ? (int)$resultado[0]['total'] : 0;
    }

    // Eliminar sesión
    public static function eliminar($id_sesion) {
        $consulta = "DELETE FROM sesiones WHERE id_sesion = ?";
        return SDPBD::consultaInsercion($consulta, $id_sesion);
    }

    // Todas las sesiones (admin)
    public static function listarTodas() {
        $consulta  = "SELECT s.*, u.nombre_usuario, m.titulo AS titulo_meditacion
                      FROM sesiones s
                      JOIN usuarios u ON s.id_usuario = u.id_usuario
                      LEFT JOIN meditaciones m ON s.id_meditacion = m.id_meditacion
                      ORDER BY s.fecha_sesion DESC LIMIT 100";
        return SDPBD::consultaLectura($consulta) ?? [];
    }
}
?>
