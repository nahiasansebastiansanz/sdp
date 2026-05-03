<?php
class CategoriaModel {

    private $id_categoria;
    private $nombre;
    private $descripcion;
    private $icono;

    public function __construct() {}

    public function getIdCategoria()   { return $this->id_categoria; }
    public function setIdCategoria($v) { $this->id_categoria = $v; }

    public function getNombre()        { return $this->nombre; }
    public function setNombre($v)      { $this->nombre = $v; }

    public function getDescripcion()   { return $this->descripcion; }
    public function setDescripcion($v) { $this->descripcion = $v; }

    public function getIcono()         { return $this->icono; }
    public function setIcono($v)       { $this->icono = $v; }
}
?>
