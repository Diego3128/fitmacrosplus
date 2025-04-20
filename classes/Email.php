<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $name;
    public $token;

    public function __construct($email, $name, $token)
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    //send an email to confirm the email
    public function sendConfirmationEmail()
    {

        //phpmailer instance
        $phpmailer = new PHPMailer();
        //settings form SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV["EMAIL_HOST"];
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $phpmailer->Port = $_ENV["EMAIL_PORT"];
        $phpmailer->Username = $_ENV["EMAIL_USERNAME"];
        $phpmailer->Password = $_ENV["EMAIL_PASSWORD"];
        //set email
        $phpmailer->setFrom($_ENV["EMAIL_USERNAME"], "fitmacros"); //domain
        $phpmailer->addAddress($this->email, $this->name);
        $phpmailer->Subject = "Autenticacion cuenta fitmacros+";
        $phpmailer->isHTML(true);
        $phpmailer->CharSet = "UTF-8";

        // styles for the email
        $content = "<html>";
        $content .= "<head>";
        $content .= "<style>";
        $content .= "body { font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }";
        $content .= ".container { max-width: 600px; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }";
        $content .= "h1 { color: #2c3e50; font-size: 24px; }";
        $content .= "p { font-size: 16px; line-height: 1.6; color: #555; }";
        $content .= "a { display: inline-block; background-color: #3498db; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }";
        $content .= "a:hover { background-color: #2980b9; }";
        $content .= "</style>";
        $content .= "</head>";
        $content .= "<body>";
        $content .= "<div class='container'>";
        $content .= "<h1>Bienvenido a FITMACROS+, {$this->name}!</h1>";
        $content .= "<p>¡Gracias por crear tu cuenta! Para activar tu cuenta y comenzar a disfrutar de nuestros servicios, haz click en el botón a continuación:</p>";
        $content .= '<p><a href="' . $_ENV["APP_URL"] . '/validate-account?token=' . $this->token . '">Confirmar cuenta</a></p>';
        $content .= "<p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>";
        $content .= "<p>Saludos,<br>El equipo de FITMACROS+</p>";
        $content .= "</div>";
        $content .= "</body>";
        $content .= "</html>";

        $phpmailer->Body = $content;

        // fallback text (in case HTML is not available)
        $phpmailer->AltBody = "Hola {$this->name},\n\n"
            . "Gracias por crear tu cuenta en FITMACROS+. Actívala visitando el siguiente enlace:\n"
            . $_ENV["APP_URL"] .  "/validate-account?token={$this->token}\n\n"
            . "Si no has solicitado esta cuenta, puedes ignorar este mensaje.\n\n"
            . "Saludos,\nEl equipo de FITMACROS+";

        if ($phpmailer->send()) {
            return true;
        } else {
            return false;
        }
    }
    //reset password
    public function sendInstructions()
    {
        //phpmailer instance
        $phpmailer = new PHPMailer();
        //settings form SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV["EMAIL_HOST"];
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $phpmailer->Port = $_ENV["EMAIL_PORT"];
        $phpmailer->Username = $_ENV["EMAIL_USERNAME"];
        $phpmailer->Password = $_ENV["EMAIL_PASSWORD"];
        //set email
        $phpmailer->setFrom($_ENV["EMAIL_USERNAME"], "fitmacros"); //domain
        $phpmailer->addAddress($this->email, $this->name);
        $phpmailer->Subject = "Recuperación contraseña cuenta fitmacros+";
        $phpmailer->isHTML(true);
        $phpmailer->CharSet = "UTF-8";

        // styles for the email
        $content = "<html>";
        $content .= "<head>";
        $content .= "<style>";
        $content .= "body { font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }";
        $content .= ".container { max-width: 600px; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }";
        $content .= "h1 { color: #2c3e50; font-size: 24px; }";
        $content .= "p { font-size: 16px; line-height: 1.6; color: #555; }";
        $content .= "a { display: inline-block; background-color: #3498db; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }";
        $content .= "a:hover { background-color: #2980b9; }";
        $content .= "</style>";
        $content .= "</head>";
        $content .= "<body>";
        $content .= "<div class='container'>";
        $content .= "<h1>Restablece tu contraseña en FITMACROS+, {$this->name}.</h1>";
        $content .= "<p>Para crear una nueva contraseña, haz click en el botón a continuación:</p>";
        $url = trim($_ENV["APP_URL"]) . '/password/reset?token=' . $this->token;
        $content .= "<p><a href=\"{$url}\">Restablecer contraseña</a></p>";
        $content .= "<p>Si no solicitaste esto puedes ignorar el mensaje.</p>";
        $content .= "<p>Saludos,<br>El equipo de FITMACROS+</p>";
        $content .= "</div>";
        $content .= "</body>";
        $content .= "</html>";

        $phpmailer->Body = $content;

        // fallback text (in case HTML is not available)
        $phpmailer->AltBody = "Restablece tu contraseña en FITMACROS+, {$this->name}.,\n\n"
            . "Para crear una nueva contraseña visita el siguiente enlace:\n"
            . $_ENV["APP_URL"] . "/password/reset?token=$this->token \n\n"
            . "Si no solicitaste esto puedes ignorar el mensaje..\n\n"
            . "Saludos,\nEl equipo de FITMACROS+";

        if ($phpmailer->send()) {
            return true;
        } else {
            return false;
        }
    }
}
