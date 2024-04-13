<?php

namespace Model;

class Usuario extends ActiveRecord {

    // declaramos las variables de la base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];
    protected static $alertas = [];

    // antes del constructor tenemos que visualizarlas (nueva version de PHP)
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    // definimos el constructor
    public function __construct($args=[])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2= $args['password2'] ?? '';
        $this->password_actual= $args['password_actual'] ?? '';
        $this->password_nuevo= $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? '0';

    }

    public function validarLogin() : array {
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "El Email no es valido";
        }
        if(!$this->password){
            self::$alertas['error'][] = "El Password no puede estar vacio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El Password requiere minimo 6 carecteres";
        }
        return self::$alertas;
        
    }


    public function validarCuentaNueva() : array{
        if(!$this->nombre){
            self::$alertas['error'][] = "El Nombre es Obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }
        if(!$this->password){
            self::$alertas['error'][] = "El Password no puede estar vacio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El Password requiere minimo 6 carecteres";
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][] = "Los Password son diferentes";

        }
        return self::$alertas;
    }

    public function validarPerfil() : array {
        if(!$this->nombre){
            self::$alertas['error'][] = "El Nombre es Obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }
        return self::$alertas;

    }

    public function nuevoPassword() :array {
        if(!$this->password_actual){
            self::$alertas['error'][]= "El Password Actual es obligatorio";
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][]= "El Password Nuevo es obligatorio";
        }
        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][]="El Password debe tene al menos 6 caracteres";
        } 
        return self::$alertas;
    }

    public function hashearPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken() : void {
        $this->token = uniqid();
    }

    public function validarEmail() : array{
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "El Email no es valido";
        }
        return self::$alertas;

    }

    public function validarPassword() : array{
        if(!$this->password){
            self::$alertas['error'][] = "El Password no puede estar vacio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El Password requiere minimo 6 carecteres";
        } 
        return self::$alertas;
    }

    public function comprobarPasswordActual(): bool { 
        return (password_verify($this->password_actual, $this->password));

    }

    




}