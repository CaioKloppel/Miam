<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();


function decryptData($encryptedData) {
    $SECRET_KEY = $_ENV['SECRET_KEY'];

    $encrypted = base64_decode($encryptedData);
    
    $salt = substr($encrypted, 8, 8);
    $ct = substr($encrypted, 16);
    
    $rounds = 3;
    $data00 = $SECRET_KEY . $salt;
    $md5_hash = [];
    $md5_hash[0] = md5($data00, true);
    $result = $md5_hash[0];
    
    for ($i = 1; $i < $rounds; $i++) {
        $md5_hash[$i] = md5($md5_hash[$i - 1] . $data00, true);
        $result .= $md5_hash[$i];
    }
    
    $key = substr($result, 0, 32);
    $iv = substr($result, 32, 16);
    
    $decrypted = openssl_decrypt($ct, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    return $decrypted;
}

function encryptData($data) {
    $SECRET_KEY = $_ENV['SECRET_KEY'];

    if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
    }
    
    $salt = openssl_random_pseudo_bytes(8);
    
    $rounds = 3;
    $data00 = $SECRET_KEY . $salt;
    $md5_hash = [];
    $md5_hash[0] = md5($data00, true);
    $result = $md5_hash[0];
    
    for ($i = 1; $i < $rounds; $i++) {
        $md5_hash[$i] = md5($md5_hash[$i - 1] . $data00, true);
        $result .= $md5_hash[$i];
    }
    
    $key = substr($result, 0, 32);
    $iv = substr($result, 32, 16);
    
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    $result = "Salted__" . $salt . $encrypted;
    
    return base64_encode($result);
}

?>