<?php
class RespiracionModel {

    private $id_respiracion;
    private $nombre;
    private $descripcion;
    private $inhala_seg;
    private $retiene_seg;
    private $exhala_seg;
    private $retiene2_seg;
    private $ciclos;

    public function __construct() {}

    public function getIdRespiracion()   { return $this->id_respiracion; }
    public function setIdRespiracion($v) { $this->id_respiracion = $v; }

    public function getNombre()          { return $this->nombre; }
    public function setNombre($v)        { $this->nombre = $v; }

    public function getDescripcion()     { return $this->descripcion; }
    public function setDescripcion($v)   { $this->descripcion = $v; }

    public function getInhalaSeg()       { return $this->inhala_seg; }
    public function setInhalaSeg($v)     { $this->inhala_seg = $v; }

    public function getRetieneSeg()      { return $this->retiene_seg; }
    public function setRetieneSeg($v)    { $this->retiene_seg = $v; }

    public function getExhalaSeg()       { return $this->exhala_seg; }
    public function setExhalaSeg($v)     { $this->exhala_seg = $v; }

    public function getRetiene2Seg()     { return $this->retiene2_seg; }
    public function setRetiene2Seg($v)   { $this->retiene2_seg = $v; }

    public function getCiclos()          { return $this->ciclos; }
    public function setCiclos($v)        { $this->ciclos = $v; }
}
?>
