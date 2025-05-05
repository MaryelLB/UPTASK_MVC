<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;

    }
    //Validacion para cuentas nuevas
    public function validarCuentaNueva() {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe ser mayor a 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password deben ser iguales';
        }

        return self::$alertas;
    }
    //Validar Email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email no Válido';
        }
    return self::$alertas;
    }
    public function validarPassword() {
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe ser mayor a 6 caracteres';
        }
    return self::$alertas;
    }
    function validarLogin() {
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email no Válido';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
    return self::$alertas;
    }
    function validarPerfil() {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }
    public function nuevoPassword() {
        if(!$this->password_actual){
            self::$alertas['error'][] = 'El password actual es obligatorio';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'El password nuevo es obligatorio';
        }
        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = 'El password actual debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password);
    }
    //Hashea el password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    //Generar un token
    public function crearToken() {
        $this->token = uniqid();
    }

}