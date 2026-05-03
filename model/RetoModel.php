<?php
class RetoModel {

    private $id_reto;
    private $titulo;
    private $descripcion;
    private $tipo;            // racha | minutos | sesiones
    private $objetivo_valor;
    private $duracion_dias;
    private $activo;

    public function __construct() {}

    public function getIdReto()          { return $this->id_reto; }
    public function setIdReto($v)        { $this->id_reto = $v; }

    public function getTitulo()          { return $this->titulo; }
    public function setTitulo($v)        { $this->titulo = $v; }

    public function getDescripcion()     { return $this->descripcion; }
    public function setDescripcion($v)   { $this->descripcion = $v; }

    public function getTipo()            { return $this->tipo; }
    public function setTipo($v)          { $this->tipo = $v; }

    public function getObjetivoValor()   { return $this->objetivo_valor; }
    public function setObjetivoValor($v) { $this->objetivo_valor = $v; }

    public function getDuracionDias()    { return $this->duracion_dias; }
    public function setDuracionDias($v)  { $this->duracion_dias = $v; }

    public function getActivo()          { return $this->activo; }
    public function setActivo($v)        { $this->activo = $v; }
}
?>
