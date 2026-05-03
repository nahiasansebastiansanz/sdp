<?php
class DiarioModel {

    private $id_entrada;
    private $id_usuario;
    private $titulo;
    private $contenido;
    private $humor;       // bien | neutral | mal
    private $fecha_entrada;

    public function __construct() {}

    public function getIdEntrada()     { return $this->id_entrada; }
    public function setIdEntrada($v)   { $this->id_entrada = $v; }

    public function getIdUsuario()     { return $this->id_usuario; }
    public function setIdUsuario($v)   { $this->id_usuario = $v; }

    public function getTitulo()        { return $this->titulo; }
    public function setTitulo($v)      { $this->titulo = $v; }

    public function getContenido()     { return $this->contenido; }
    public function setContenido($v)   { $this->contenido = $v; }

    public function getHumor()         { return $this->humor; }
    public function setHumor($v)       { $this->humor = $v; }

    public function getFechaEntrada()  { return $this->fecha_entrada; }
    public function setFechaEntrada($v){ $this->fecha_entrada = $v; }
}
?>
