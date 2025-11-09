<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();


function decryptDataSymmetric($encryptedData, $SECRET_KEY): string {
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

function encryptDataSymmetric($data, $SECRET_KEY): string {
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

function decryptDataAssymetric(string $data, $privateKeyPath= __DIR__ . '/../private_key.pem'){
    $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
    
    $encrypted = base64_decode($data);
    
    openssl_private_decrypt($encrypted, $decrypted, $privateKey);
    
    return $decrypted;
}


function encryptDataAssymetric(string $data, $privateKeyPath= __DIR__ . '/../private_key.pem'){
    if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
    }
    
    $publicKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
    
    openssl_private_encrypt($data, $encrypted, $publicKey);
    
    return base64_encode($encrypted);
}

function generateRandomKey(): string{
    $randomKey = openssl_random_pseudo_bytes(32);
    $randomKeyHex = bin2hex($randomKey);

    return $randomKeyHex;
}

function encryptResponse(array $responseData): string {
    $randomKey = generateRandomKey();
    
    $encrypted = encryptDataSymmetric($responseData, $randomKey);
    $encryptedKey = encryptDataAssymetric($randomKey);
    
    return json_encode([
        'data' => $encrypted,
        'key' => $encryptedKey
    ]);
}
?>
