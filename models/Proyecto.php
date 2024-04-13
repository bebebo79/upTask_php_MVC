<?php

namespace Model;
use Model\ActiveRecord;

class Proyecto extends ActiveRecord {

    // declaramos las variables de la base de datos
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','proyecto','url','propietarioId'];
    protected static $alertas= [];

    // antes del constructos declaramos las variables publicas

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;

    // las pasamos al constructor
    public function __construct($args=[])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    //validamos los campos
    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][]='El Nombre del Proyecto es Obligatorio';
        }
        return self::$alertas;
    }   
    
}