<?php
// Adapter Pattern for reCAPTCHA integration

interface CaptchaVerifierInterface {
    public function verify(?string $token, string $ipAddress): bool;
    public function getSiteKey(): string;
}

/**
 * Adaptee that talks directly to Google's reCAPTCHA verify API.
 */
class GoogleRecaptchaService {
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = trim($secretKey);
    }

    public function validate(?string $token, string $ipAddress): bool
    {
        $token = trim((string) $token);
        if ($this->secretKey === '' || $token === '') {
            return false;
        }

        $payload = http_build_query([
            'secret'   => $this->secretKey,
            'response' => $token,
            'remoteip' => $ipAddress,
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 5,
            ],
        ];

        $context = stream_context_create($opts);
        $result = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($result === false) {
            return false;
        }

        $json = json_decode($result, true);
        return is_array($json) && !empty($json['success']);
    }
}

/**
 * Adapter that exposes a simple interface to the controller layer.
 */
class RecaptchaAdapter implements CaptchaVerifierInterface {
    private $service;
    private $siteKey;

    public function __construct(string $siteKey, string $secretKey)
    {
        $this->siteKey = trim($siteKey);
        $this->service = new GoogleRecaptchaService($secretKey);
    }

    public function verify(?string $token, string $ipAddress): bool
    {
        return $this->service->validate($token, $ipAddress);
    }

    public function getSiteKey(): string
    {
        return $this->siteKey;
    }
}
