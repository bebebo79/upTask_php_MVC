<?php 


namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;


class LoginController {
    
    public static function login(Router $router) {
        
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            // si no hay alertas, y los campos estan validados..
            if(empty($alertas)){
                //verificamos que el usuario existe en la base de datos
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no exite o no esta confirmado');
                } else {
                    // verificar el password
                    if(password_verify($_POST['password'], $usuario->password)){
                        // iniciar sesion
                        session_start();
                        // llenamos el array de la sesion
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //redireccionar al usuario
                        header ('Location:/dashboard');

                       
                    }else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }

                }               
            }
            
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas'=> $alertas

        ]);


        
    }

    public static function logout(){
       session_start();
       $_SESSION = [];
       header('Location:/');
    }
    
    
    public static function crear(Router $router) {
        // instanciamos el modelo de Usuario
        $usuario = new Usuario;
        $alertas = [];
        

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // sincronizar los datos que metemos en Post con usuario
            $usuario->sincronizar($_POST);
            
            // validamos los datos
            $usuario->validarCuentaNueva();
            
            
            // si ya no hay alertas y estan todos los campos validados
            if(empty($alertas)){
                // comprobar que el usuario ya no estaba registrado
                $usuarioYaRegistrado = Usuario::where('email', $usuario->email);

                if($usuarioYaRegistrado){
                    Usuario::setAlerta('error','El usuario ya existe');
                    $alertas = Usuario::getAlertas();

                }
                else {
                    //hasheamos el password 
                    $usuario->hashearPassword();
                
                    //eliminar el elemento Password2 del objeto usuario
                    unset($usuario->password2);

                    //generamos un token
                    $usuario->generarToken();

                    //creamos el usuario
                    $resultado = $usuario->guardar();
                
                    if($resultado) {
                    
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarConfirmacion();

                        header('Location: /mensaje');

                    }
                }    
            }        
           

        }
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/crear',[
            'titulo'=>'Crear Cuenta',
            'usuario' => $usuario, 
            'alertas' => $alertas

        ]);    
    }

    public static function olvide(Router $router) {  
        $alertas = [];      
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // buscar al usuario
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado){
                    //generar un token
                    $usuario->generarToken();
                    unset($usuario->password2);
                    
                    //actualizar el usuario
                    $resultado= $usuario->guardar();

                    //mandar el email de reestablecer
                    if($resultado){
                        $email= new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarInstrucciones();
                        
                    }
                    
                    // mandar el mensaje de exito
                    Usuario::setAlerta('exito', 'Hemos mandado las instrucciones a tu mail');
                    
                    
                }
                else {
                    Usuario::setAlerta('error','El usuario no exite o falta confirmación');
                    
                }

            }


        }
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo'=> 'Olvide el Password',
            'alertas'=> $alertas
            
        ]);
        
    }

    public static function reestablecer(Router $router) {
       
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('Location:/');
        
        //identificar el usuario con este token
        $usuario = Usuario::where('token', $token);
        if(!$usuario){
            $alertas = Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }  
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                //hashear password
                $usuario->hashearPassword();
                
                //eliminar el contenido del token
                $usuario->token = null;
                
                //guardar usuario
                $resultado = $usuario->guardar();

                //redireccionar
                if($resultado) {
                    header ('Location: /');
                }

            }

        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer el Password',
            'alertas'=> $alertas,
            'mostrar'=> $mostrar
        ]);
        
    }

    public static function mensaje(Router $router) {
       
        $router->render('auth/mensaje',[
            'titulo'=>'Cuenta creada con Exito'
        ]);
    }

    public static function confirmar(Router $router) {
        // crear la variable token
        $token = s($_GET['token']);
        // si no hay token volver al inicio
        if(!$token){
            header('Location:/');
        }
        //encontrar al usuario por el token
        $usuario = Usuario::where('token', $token);

        //si el token que hay no es valido
        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
        } else{
            //confirmar el usuario
            // pasamos el confirmado a 1
            $usuario->confirmado = 1;
            //borramos el token
            $usuario->token = null;
            // borramos el registro de password2
            unset($usuario->password2);

            // guardamos el usuario confirmado mediante el mail
            $usuario->guardar();

            //mandamos una alerta al usuario de exito
            Usuario::setAlerta('exito', 'Cuenta Completada con Exito');
        }

       

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/confirmar',[
            'titulo'=> 'Cuenta Confirmada',
            'alertas'=> $alertas
        ]);

        
    }
}