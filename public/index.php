<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\tareaController;
use MVC\Router;
$router = new Router();


//////// LOGIN  AUTH /////////

// iniciar sesion // 
$router->get('/',[LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
// cerrar sesion  ///
$router->get('/logout', [LoginController::class, 'logout']);

//**** CUENTA *******/
// crear //
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);
// olvide password//
$router->get('/olvide',[LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// restablecer la contraseña 
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

// mensajes de confirmacion de cambios
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);


/////// DASHBOARD ZONA PRIVADA ///////////
$router->get('/dashboard',[DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);




//// API para las tareas del CRUD ////
$router->get('/api/tareas', [tareaController::class, 'index']);
$router->post('/api/tarea', [tareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [tareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [tareaController::class, 'eliminar']);






// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();