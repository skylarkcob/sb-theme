<?php
if(!defined("ABSPATH")) {
    exit;
}
class SB_Data {
    private $key, $iv;
    public function __construct($key) {
        $this->key = hash('sha256', $key, true);
        $this->iv = mcrypt_create_iv(32);
    }

    public function encrypt($plaint_text) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $plaint_text, MCRYPT_MODE_ECB, $this->iv));
    }

    public function decrypt($cipher_text) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, base64_decode($cipher_text), MCRYPT_MODE_ECB, $this->iv));
    }
}