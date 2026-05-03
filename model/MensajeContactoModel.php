<?php
class MensajeContactoModel {

    private $id_mensaje;
    private $id_usuario;
    private $nombre_usuario;
    private $nombre;
    private $email;
    private $asunto;
    private $mensaje;
    private $fecha_envio;

    public function __construct() {}

    public function getIdMensaje()    { return $this->id_mensaje; }
    public function setIdMensaje($v)   { $this->id_mensaje = $v; }

    public function getIdUsuario()       { return $this->id_usuario; }
    public function setIdUsuario($v)    { $this->id_usuario = $v; }

    public function getNombreUsuario()  { return $this->nombre_usuario ?? null; }
    public function setNombreUsuario($v) { $this->nombre_usuario = $v; }

    public function getNombre()      { return $this->nombre; }
    public function setNombre($v)     { $this->nombre = $v; }

    public function getEmail()       { return $this->email; }
    public function setEmail($v)     { $this->email = $v; }

    public function getAsunto()      { return $this->asunto; }
    public function setAsunto($v)    { $this->asunto = $v; }

    public function getMensaje()     { return $this->mensaje; }
    public function setMensaje($v)   { $this->mensaje = $v; }

    public function getFechaEnvio()  { return $this->fecha_envio; }
    public function setFechaEnvio($v) { $this->fecha_envio = $v; }
}
?>
