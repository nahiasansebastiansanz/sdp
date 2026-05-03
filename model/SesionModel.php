<?php
class SesionModel {

    private $id_sesion;
    private $id_usuario;
    private $tipo;          // libre | guiada | respiracion
    private $duracion_min;
    private $id_meditacion;
    private $con_gong;
    private $fecha_sesion;

    public function __construct() {}

    public function getIdSesion()      { return $this->id_sesion; }
    public function setIdSesion($v)    { $this->id_sesion = $v; }

    public function getIdUsuario()     { return $this->id_usuario; }
    public function setIdUsuario($v)   { $this->id_usuario = $v; }

    public function getTipo()          { return $this->tipo; }
    public function setTipo($v)        { $this->tipo = $v; }

    public function getDuracionMin()   { return $this->duracion_min; }
    public function setDuracionMin($v) { $this->duracion_min = $v; }

    public function getIdMeditacion()  { return $this->id_meditacion; }
    public function setIdMeditacion($v){ $this->id_meditacion = $v; }

    public function getConGong()       { return $this->con_gong; }
    public function setConGong($v)     { $this->con_gong = $v; }

    public function getFechaSesion()   { return $this->fecha_sesion; }
    public function setFechaSesion($v) { $this->fecha_sesion = $v; }
}
?>
