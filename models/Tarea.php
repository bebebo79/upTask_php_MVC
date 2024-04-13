<?php

namespace Model;
use Model\ActiveRecord;


class Tarea extends ActiveRecord {

    // declaramos las variables de la base de datos
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id','nombre','estado', 'proyectoId'];
    protected static $alertas = [];

    // antes del constructor tenemos que visualizarlas (nueva version de PHP)
    public $id;
    public $nombre;
    public $estado;
    public $proyectoId;

    // definimos el constructor
    public function __construct($args=[])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->proyectoId = $args['proyectoId'] ?? '';

    }



}