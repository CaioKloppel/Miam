<?php

function decryptData($encryptedData, $privateKeyPath= __DIR__ . '/../../private_key.pem') {
    $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
    
    $encrypted = base64_decode($encryptedData);
    
    openssl_private_decrypt($encrypted, $decrypted, $privateKey);
    
    return $decrypted;
}

function encryptData($data, $privateKeyPath= __DIR__ . '/../../private_key.pem') {
    
    if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
    }
    
    $publicKey = openssl_pkey_get_public(file_get_contents($privateKeyPath));
    
    openssl_public_encrypt($data, $encrypted, $publicKey);
    
    return base64_encode($encrypted);
}

?>