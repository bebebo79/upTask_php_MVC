<?php
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;





class tareaController {

    public static function index(){
        session_start();

        $proyectoId = $_GET['id'];
        // en el caso que no tengamos el id del proyecto
        if(!$proyectoId) header('Location:/dahshboard');
        
        // nos traemos el proyecto de ese id
        $proyecto = Proyecto::where('url', $proyectoId);

        // en el caso que no haya proyecto o el usuario no sea el que ha creado el proyecto
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header ('Location:/404');

        // nos traemos las tareas de ese proyecto
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);
        
    }

    public static function crear(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            
            $proyectoId = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoId);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=> 'error',
                    'mensaje' => 'hubo un error al añadir la tarea'
                ];
                echo json_encode($respuesta);
                return;

            } 
            // esta todo bien instanciamos la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo'=> 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Agregada Correctamente',
                'proyectoId'=>$proyecto->id
            ];

            echo json_encode($respuesta);

           
        }

        

    }
    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();

            // validar que el proyecto exita
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=> 'error',
                    'mensaje' => 'hubo un error al añadir la tarea'
                ];
                echo json_encode($respuesta);
                return;

            }
            //esta todo bien es momento de actualizar

            // instanciamos la tarea
            $tarea = new Tarea($_POST);
            
            // convertimos la url del proyecto en el id del proyecto
            $tarea->proyectoId = $proyecto->id;
            
            //guardamos el cambio
            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta = [
                    'tipo'=> 'exito',
                    'id' => $tarea->id,
                    'proyectoId'=>$proyecto->id,
                    'mensaje' => "Actualizado Correctamente"
                ];
                echo json_encode(['respuesta' =>$respuesta]);
            }
        }


       

    }
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();

            // validar que el proyecto exita
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=> 'error',
                    'mensaje' => 'hubo un error al añadir la tarea'
                ];
                echo json_encode($respuesta);
                return;

            }
            //esta todo validado pues a eliminar
            
            //instanciamos tarea
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado Correctamente',
                'tipo' =>'exito'
            ];
            
            
            echo json_encode($resultado);
            
            
        }


       
    }
}