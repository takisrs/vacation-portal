<?php

namespace takisrs\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Handles email templating and sending
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class EmailTemplate
{
    /**
     * @var string $templatePath the path to the html template file
     */
    private $templatePath;

    /**
     * @var string $subject email's subject
     */
    private $subject;

    /**
     * @var string $body email's html content
     */
    private $body;

    /**
     * Constructor
     * 
     * @param sting $templatePath the path to the html template file
     */
    public function __construct(string $templatePath)
    {
        if (!file_exists($templatePath))
            throw new \Exception("Email template not found: " . $templatePath);

        $this->templatePath = $templatePath;
        $this->loadBody();
        $this->extractSubject();
    }

    /**
     * Loads the html content of the template file
     *
     * @return void
     */
    private function loadBody(): void
    {
        $this->body = file_get_contents($this->templatePath);
    }

    /**
     * Extracts the email subject from the title tag of the html page
     *
     * @return void
     */
    private function extractSubject(): void
    {
        preg_match("/<title>(.*)<\/title>/siU", $this->body, $titleMatches);
        $this->subject = $titleMatches[1];
    }

    /**
     * Replaces a variable in the html content with the provided value
     *
     * @param string $key the variable name
     * @param string|int $value the actual value
     * @return self
     */
    public function replaceVar($key, $value): self
    {
        $this->body = str_replace("{" . $key . "}", $value, $this->body);
        return $this;
    }

    /**
     * Replaces all the variables in the html content of the email template
     *
     * @param array $data
     * @return self
     */
    public function replaceVars($data): self
    {
        foreach ($data as $key => $value)
            $this->replaceVar($key, $value);
        return $this;
    }

    /**
     * Returns email's subject
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Returns email's body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sends the email
     *
     * @param string $email the recipient
     * @return void
     */
    public function send(string $email): void
    {
        $mail = new PHPMailer();

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            //Recipients
            $mail->setFrom($_ENV['FROM_EMAIL'], $_ENV['FROM_NAME']);
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $this->getSubject();
            $mail->Body    = $this->getBody();
            $mail->AltBody = strip_tags($this->getBody());

            $mail->send();

        } catch (Exception $e) {
            // Does nothing right now. You could log an error though.
        }

    }
}
