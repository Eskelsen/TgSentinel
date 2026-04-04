<?php

class Crypt
{
    private const CIPHER = 'aes-256-gcm';

    public static function key($token)
    {
        return hash('sha1', $token, true);
    }

    public static function encrypt($plaintext, $key)
    {
        $iv = random_bytes(openssl_cipher_iv_length(self::CIPHER));
        $tag = '';

        $cipher = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return base64_encode($iv . $tag . $cipher);
    }

    public static function decrypt($data, $key)
    {
        $raw = base64_decode($data);

        $ivLength = openssl_cipher_iv_length(self::CIPHER);

        $iv = substr($raw, 0, $ivLength);
        $tag = substr($raw, $ivLength, 16);
        $cipher = substr($raw, $ivLength + 16);

        return openssl_decrypt(
            $cipher,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
    }
}
