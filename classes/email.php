<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class email {

        public $email;
        public $nombre;
        public $token;
    
        public function __construct($email, $nombre, $token) 
        {
            $this->email = $email;
            $this->nombre = $nombre;
            $this->token = $token;
        }
    
        public function enviarConfirmacion() 
        {
            //Crear el objeto de email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port =  $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
    
            $mail->setFrom('cuentas@uptask.com');
            $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
            $mail->Subject = 'Confirma tu cuenta';
    
            //Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';
    
            $contenido = "<html>";
            $contenido .= "<p>Hola <strong>". $this->nombre ."</strong> Has creado tu cuenta en UpTask, solo debes
            de confirmarla presionando el siguiente enlace</p>";
            $contenido .= "<p>Presiona aqui: <a href='". $_ENV['HOST']. "/confirmar?token=" .$this->token. "'>Confirmar cuenta</a></p>";
            $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .= "</html>";
    
            $mail->Body = $contenido;
    
            //Enviar el mail
    
            $mail->send();
        }

        public function enviarInstrucciones() {
            //Crear el objeto de email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port =  $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
    
            $mail->setFrom('cuentas@uptask.com');
            $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
            $mail->Subject = 'Reestablece tu contraseña';

            //Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';
    
            $contenido = "<html>";
            $contenido .= "<p>Hola <strong>". $this->nombre ."</strong> Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
            $contenido .= "<p>Presiona aqui: <a href='". $_ENV['HOST']. "/reestablecer?token=" .$this->token. "'>Reestablecer password</a></p>";
            $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .= "</html>";
    
            $mail->Body = $contenido;

            //Enviar el mail
            $mail->send();
        }
    
    }
