<?php
class UsuarioModel {

    private $id_usuario;
    private $nombre_usuario;
    private $contrasena_hash;
    private $nombre_completo;
    private $email;
    private $edad;
    private $genero;
    private $telefono;
    private $perfil;
    private $fecha_alta;

    public function __construct() {}

    public function getIdUsuario()        { return $this->id_usuario; }
    public function setIdUsuario($v)      { $this->id_usuario = $v; }

    public function getNombreUsuario()    { return $this->nombre_usuario; }
    public function setNombreUsuario($v)  { $this->nombre_usuario = $v; }

    public function getContrasenaHash()   { return $this->contrasena_hash; }
    public function setContrasenaHash($v) { $this->contrasena_hash = $v; }

    public function getNombreCompleto()   { return $this->nombre_completo; }
    public function setNombreCompleto($v) { $this->nombre_completo = $v; }

    public function getEmail()            { return $this->email; }
    public function setEmail($v)          { $this->email = $v; }

    public function getEdad()             { return $this->edad; }
    public function setEdad($v)           { $this->edad = $v; }

    public function getGenero()           { return $this->genero; }
    public function setGenero($v)         { $this->genero = $v; }

    public function getTelefono()         { return $this->telefono; }
    public function setTelefono($v)       { $this->telefono = $v; }

    public function getPerfil()           { return $this->perfil; }
    public function setPerfil($v)         { $this->perfil = $v; }

    public function getFechaAlta()        { return $this->fecha_alta; }
    public function setFechaAlta($v)      { $this->fecha_alta = $v; }
}
?>
