<?php
namespace App\Api;

class ApiCommunication
{
    private $secretKey;
    private $environment;

    public function __construct()
    {
        $this->secretKey = SECRET_INTERNAL_KEY;
        $this->environment = APP_ENV;
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function generateHash($id_clinica_veterinaria_fk, $id_usuario, $timestamp)
    {
        $data = $id_clinica_veterinaria_fk . $id_usuario .  $this->environment . $timestamp;
        return hash_hmac('sha256', $data, $this->secretKey);
    }

    public function generateBase64Hash($hash)
    {
        return base64_encode($hash);
    }

    public function sendRequest($url, $id_clinica_veterinaria_fk, $id_usuario)
    {
        $timestamp = time();
        $hash = $this->generateHash($id_clinica_veterinaria_fk, $id_usuario, $timestamp);
        $base64Hash = $this->generateBase64Hash($hash);

        $headers = [
            'id_clinica_veterinaria: ' . $id_clinica_veterinaria_fk,
            'id_usuario: ' . $id_usuario,
            'timestamp: ' . $timestamp,
            'environment: ' . $this->environment,
            'hash: ' . $base64Hash
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

