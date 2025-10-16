<?php
namespace App\Api;

class ApiReceiver
{
    private $secretKey;
    private $environment;

    public function __construct()
    {
        $this->secretKey = SECRET_INTERNAL_KEY;
        $this->environment = APP_ENV;
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function verifyHash($id_clinica_veterinaria_fk, $id_usuario, $timestamp, $receivedHash)
    {
        $data = $id_clinica_veterinaria_fk . $id_usuario . $this->environment . $timestamp;
        $calculatedHash = hash_hmac('sha256', $data, $this->secretKey);

        return hash_equals($calculatedHash, $receivedHash);
    }

    public function verifyHashManually($id_clinica_veterinaria_fk, $id_usuario, $timestamp, $hash)
    {
        $receivedHash = base64_decode($hash);

        return $this->verifyHash($id_clinica_veterinaria_fk, $id_usuario, $timestamp, $receivedHash);
    }

    public function processRequest($id_clinica_veterinaria_fk, $id_usuario, $timestamp, $hash)
    {
        
        $isHashValid = $this->verifyHashManually($id_clinica_veterinaria_fk, $id_usuario, $timestamp, $hash);

        if ($isHashValid) {

            if (time() - $timestamp <= 300) {  // 300 segundos = 5 minutos
                return true;
            }
            return false;  // Timestamp inválido (expirado)
        }

        

        return false;  // Hash inválido
    }
}