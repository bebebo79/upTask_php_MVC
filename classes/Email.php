<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $nombre;
    protected $email;
    protected $token;

    public function __construct($nombre, $email, $token) 
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
        
    }
    // Libreria de phpMailer
    public function enviarConfirmacion(){
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = $_ENV['EMAIL_HOST'];
        $email->SMTPAuth = true;
        $email->Port = $_ENV['EMAIL_PORT'];
        $email->Username = $_ENV['EMAIL_USER'];
        $email->Password = $_ENV['EMAIL_PASS'];

        $email->setFrom('cuentas@uptask.com');
        $email->addAddress('cuentas@uptask.com', 'uptask.com');
        $email->Subject = "Confirma tu Cuenta";

        //set html
        $email->isHTML(true);
        $email->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has creado una cuenta en UpTask, solo tienes que confirmarla presionanado el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui : <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste la cuenta puedes ignorar este mail</p>";
        $contenido .= "</html>";


        $email->Body  = $contenido;

        // mandar el correo
        $email->send();
    }   
    public function enviarInstrucciones() {
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = $_ENV['EMAIL_HOST'];
        $email->SMTPAuth = true;
        $email->Port = $_ENV['EMAIL_PORT'];
        $email->Username = $_ENV['EMAIL_USER'];
        $email->Password = $_ENV['EMAIL_PASS'];


        $email->setFrom('cuentas@uptask.com');
        $email->addAddress('cuentas@uptask.com', 'uptask.com');
        $email->Subject = "Restablecer Password";

        //set html
        $email->isHTML(true);
        $email->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu Password, siga las indicaciones pinchado el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui : <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=" . $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no creaste la cuenta puedes ignorar este mail</p>";
        $contenido .= "</html>";


        $email->Body  = $contenido;

        // mandar el correo
        $email->send();
      
    }



}

