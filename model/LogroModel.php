<?php
class LogroModel {

    private $id_logro;
    private $titulo;
    private $descripcion;
    private $icono;
    private $condicion_tipo;   // sesiones | minutos | racha
    private $condicion_valor;

    public function __construct() {}

    public function getIdLogro()         { return $this->id_logro; }
    public function setIdLogro($v)       { $this->id_logro = $v; }

    public function getTitulo()          { return $this->titulo; }
    public function setTitulo($v)        { $this->titulo = $v; }

    public function getDescripcion()     { return $this->descripcion; }
    public function setDescripcion($v)   { $this->descripcion = $v; }

    public function getIcono()           { return $this->icono; }
    public function setIcono($v)         { $this->icono = $v; }

    public function getCondicionTipo()   { return $this->condicion_tipo; }
    public function setCondicionTipo($v) { $this->condicion_tipo = $v; }

    public function getCondicionValor()  { return $this->condicion_valor; }
    public function setCondicionValor($v){ $this->condicion_valor = $v; }
}
?>
