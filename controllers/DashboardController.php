<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);
        
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)) {
                //Generar una URL única
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                //Guardar Proyecto
                $proyecto->guardar();

                //Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }
        $alertas = Proyecto::getAlertas();
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        
        session_start();
        isAuth();
        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');
        //Revisar que la persona que visita el proyecto, es quien lo creo
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }
        
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
        ]);
    }

    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {
                //Verificar que el email no exista de otro usuario
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //Mostrar un mensaje de error
                    Usuario::setAlerta('error', 'Email no valido, ya pertenece a otra cuenta');
                } else {
                    //Guardar el registro;
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    //Asignar el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            // Sincronizar los datos con el usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevoPassword();
            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password();
                if($resultado) {
                    //Asignar el nuevo password;
                    $usuario->password = $usuario->password_nuevo;
                    
                    //Eliminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    //Hashear el nuevo password
                    $usuario->hashPassword();
                    //Actualizar el nuevo password
                    $resultado = $usuario->guardar();
                    if($resultado) {}
                    Usuario::setAlerta('exito', 'Password Guardado con exito');
                } else {
                    Usuario::setAlerta('error', 'El password actual es incorrecto');
                }
            }
            
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar password',
            'alertas' => $alertas
        ]);
    }
}