<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;




class DashboardController {
    
    public static function index(Router $router){
        //iniciar sesion
        session_start();
        //comprobar que esta autenticado
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

       
       

        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos

        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //generamos una url
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //identificamos al propietario
                $proyecto->propietarioId = $_SESSION['id'];
                
                //guardamos en la base de datos
                $proyecto->guardar();

                //redireccionar al proyecto que estamos creando
                header('Location: /proyecto?id='. $proyecto->url);
            
               
            }
        }    
        $router->render('dashboard/crear-proyecto',[
            'alertas'=>$alertas,
            'titulo' => 'Crear Proyecto',
            

        ]);

    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();
      
        
        $token = $_GET['id'];
        //para proteger la url
        if(!$token) header('Location:/dashboard');
        
        //instanciamos sobre la url para pillar los datos del proyecto
        $proyecto =  Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }


        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto

        ]);

    }
    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];

       $usuario = Usuario::find($_SESSION['id']);

       if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // SINCRONIZAMOS
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){
                $correoExiste = Usuario::where('email', $usuario->email);
                if($correoExiste && $correoExiste->id !== $usuario->id){
                    // mensaje de error
                    Usuario::setAlerta('error', 'Email no valido, ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();

                } else{
                    //guardamos el usuario
                    $usuario->guardar();
                    //alerta de exito
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();
                    // hacer el cambio en la barra de Hola:
                    $_SESSION['nombre'] = $usuario->nombre;
                }  

                

            }

       }
       

        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'alertas' =>$alertas,
            'usuario' => $usuario
              


        ]);

    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario = Usuario::find($_SESSION['id']);

            //sincronizamos cualquier cambio
            $usuario->sincronizar($_POST);
            
            //validar que los campos no esten vacios
            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)){
                //comprobar que el password_actual es el password
                $resultado = $usuario->comprobarPasswordActual();
                
                if($resultado){
                    // asignar passwordNuevo a Password
                    $usuario->password = $usuario->password_nuevo;
                    
                    //eliminamos los elementos del objeto que no queremos
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hasheamos el password
                    $usuario->hashearPassword();

                    //guardamos la contraseña en la base de datos
                    $resultado = $usuario->guardar();
                    //alerta de cambio exitoso
                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Cambiado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }


                }else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();

                }

            }
        }            
    

        $router->render('dashboard/cambiar-password', [
            'titulo'=>'Cambiar Password', 
            'usuario'=> $usuario,
            'alertas'=>$alertas


        ]);

    }
    
}