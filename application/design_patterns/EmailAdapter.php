<?php
// Adapter Pattern for Email Service
interface EmailServiceInterface {
    public function send($to, $subject, $message);
}

class PHPMailerAdapter implements EmailServiceInterface {
    public function send($to, $subject, $message) {
        // Example: integrate with PHPMailer or any other library
        // For demonstration, just return a string
        return "Email sent to $to with subject '$subject'";
    }
}

class EmailSender {
    private $service;
    public function __construct(EmailServiceInterface $service) {
        $this->service = $service;
    }
    public function sendEmail($to, $subject, $message) {
        return $this->service->send($to, $subject, $message);
    }
}
