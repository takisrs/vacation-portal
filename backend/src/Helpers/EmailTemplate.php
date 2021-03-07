<?php

namespace takisrs\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailTemplate
{
    private $templatePath;
    private $subject;
    private $body;

    public function __construct(string $templatePath)
    {
        if (!file_exists($templatePath))
            throw new \Exception("Email template not found: " . $templatePath);

        $this->templatePath = $templatePath;
        $this->loadBody();
        $this->extractSubject();
    }

    private function loadBody()
    {
        $this->body = file_get_contents($this->templatePath);
    }

    private function extractSubject()
    {
        preg_match("/<title>(.*)<\/title>/siU", $this->body, $titleMatches);
        $this->subject = $titleMatches[1];
    }

    public function replaceVar($key, $value)
    {
        $this->body = str_replace("{" . $key . "}", $value, $this->body);
        return $this;
    }

    public function replaceVars($data)
    {
        foreach ($data as $key => $value)
            $this->replaceVar($key, $value);
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function send($email)
    {
        $mail = new PHPMailer();

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = 'smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = '1b3b31c3f8e075';
            $mail->Password   = '4119bd75e6978b';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 25;

            //Recipients
            $mail->setFrom('takispadaz@gmail.com', 'Panos Pantazopoulos');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $this->getSubject();
            $mail->Body    = $this->getBody();
            $mail->AltBody = strip_tags($this->getBody());

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
}
