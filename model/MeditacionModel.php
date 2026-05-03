<?php
class MeditacionModel {

    private $id_meditacion;
    private $titulo;
    private $descripcion;
    private $id_categoria;
    private $nombre_categoria;
    private $nivel;
    private $duracion_min;
    private $icono;
    private $archivo_audio;
    private $instrucciones;

    public function __construct() {}

    public function getIdMeditacion()      { return $this->id_meditacion; }
    public function setIdMeditacion($v)    { $this->id_meditacion = $v; }

    public function getTitulo()            { return $this->titulo; }
    public function setTitulo($v)          { $this->titulo = $v; }

    public function getDescripcion()       { return $this->descripcion; }
    public function setDescripcion($v)     { $this->descripcion = $v; }

    public function getIdCategoria()       { return $this->id_categoria; }
    public function setIdCategoria($v)     { $this->id_categoria = $v; }

    public function getNombreCategoria()   { return $this->nombre_categoria; }
    public function setNombreCategoria($v) { $this->nombre_categoria = $v; }

    public function getNivel()             { return $this->nivel; }
    public function setNivel($v)           { $this->nivel = $v; }

    public function getDuracionMin()       { return $this->duracion_min; }
    public function setDuracionMin($v)     { $this->duracion_min = $v; }

    public function getIcono()             { return $this->icono; }
    public function setIcono($v)           { $this->icono = $v; }

    public function getArchivoAudio()      { return $this->archivo_audio; }
    public function setArchivoAudio($v)    { $this->archivo_audio = $v; }

    public function getInstrucciones()     { return $this->instrucciones; }
    public function setInstrucciones($v)   { $this->instrucciones = $v; }
}
?>
