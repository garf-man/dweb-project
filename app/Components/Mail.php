<?php


namespace App\Components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mail
{

        protected  $mail;



        public function __construct(array $config)
        {
            $this->mail = new PHPMailer(true);
            //Server settings
//			$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $this->mail->isSMTP();                                      // Set mailer to use SMTP
            $this->mail->Host = $config['host']; // Specify main and backup SMTP servers
            $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
            $this->mail->Username = $config['username'];                 // SMTP username
            $this->mail->Password = $config['password'];                           // SMTP password
            $this->mail->SMTPSecure = $config['smtpsecure'];                            // Enable TLS encryption, `ssl` also accepted
            $this->mail->Port = $config['port'];

        }
        public function sendMail($email,$textMail){

        // Passing `true` enables exceptions
        try {
            //Server settings
//			$mail->SMTPDebug = 2;                                 // Enable verbose debug output
                                                // Set mailer to use SMTP
            $this->mail->Host = 'smtp.mail.ru';  // Specify main and backup SMTP servers
            $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
            $this->mail->Username = 'pavelmailer';                 // SMTP username
            $this->mail->Password = '340402698A';                           // SMTP password
            $this->mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $this->mail->Port = 465;                                    // TCP port to connect to

			//Recipients
            $this->mail->CharSet='UTF-8';
            $this->mail->setFrom('pavelmailer@mail.ru');
            $this->mail->addAddress($email);     // Add a recipient              // Name is optional
           // $mail->addAddress('pavel.utlik@gmail.com');     // Add a recipient
            $this->mail->addReplyTo('pavel.utlik2@gmail.com');

			//Content
            $this->mail->isHTML(true);                                  // Set email format to HTML
            $this->mail->Subject = 'Тема письма';
            $this->mail->Body    = $textMail;
            $this->mail->AltBody = 'Альтернативное письмо';

            $this->mail->send();
		} catch (Exception $e) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $this->mail->ErrorInfo;
		}
	}

}
