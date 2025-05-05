<?php 

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\email;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);
                //Verificar si el usuario existe
                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    //El usuario existe
                    if(password_verify($_POST['password'], $usuario->password)) {
                        //Iniciar sesion
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router) {
        $usuario = new Usuario();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();
            
            if(empty($alertas)) {
                //Validar que el usuario no exista
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Crear el nuevo usuario
                    //Hashear el password
                    $usuario->hashPassword();
                    //Eliminar password2
                    unset($usuario->password2);
                    //Generar el token
                    $usuario->crearToken();
                    $email = new email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    //Crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado) {
                    //Generar un nuevo token
                    $usuario->crearToken();
                    //Actualizar el token
                    $usuario->guardar();
                    //Enviar el email con las instrucciones
                    $email = new email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //Imprimir la alerta que se han enviando las instrucciones
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                }
            }
            //debuguear($existeUsuario);
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Olvide password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer( Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $mostrar = true;
        if(!$token) header('Location: /');

        //Identificar el usuario con el token 
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                //Hashear el nuevo password
                $usuario->hashPassword();
                //Eliminar el token
                $usuario->token = null;
                //Guardar nuevo password
                $resultado = $usuario->guardar();
                //Redireccionar
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        //Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router) {
        
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);

        
    }
    public static function confirmar(Router $router) {
        $token = s($_GET['token']);
        if(!$token) {
            header('Location: /');
        }

        //Enocntrar al usuario con este token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            //No se encontro un usuario con este token
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            //Confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            
            //Guardar en la base de datos
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta Creada Exitosamente',
            'alertas' => $alertas
        ]);
    }
}