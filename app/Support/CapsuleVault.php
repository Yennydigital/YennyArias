<?php

namespace App\Support;

/**
 * El mensaje se guarda cifrado con AES-256-GCM. La clave sale del código
 * (PBKDF2) y el código nunca se guarda: solo un HMAC para poder buscarlo.
 */
class CapsuleVault
{
    // Sin I, L, O, 0 ni 1 para evitar confusiones al escribir el código.
    private const ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
    private const LENGTH = 12;
    private const ROUNDS = 100000;

    public static function newCode(): string
    {
        $max = strlen(self::ALPHABET) - 1;
        $raw = '';
        for ($i = 0; $i < self::LENGTH; $i++) {
            $raw .= self::ALPHABET[random_int(0, $max)];
        }

        return implode('-', str_split($raw, 4));
    }

    public static function normalize(string $code): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $code));
    }

    public static function isValidFormat(string $code): bool
    {
        $code = self::normalize($code);

        return strlen($code) === self::LENGTH
            && strspn($code, self::ALPHABET) === self::LENGTH;
    }

    public static function lookup(string $code): string
    {
        return hash_hmac('sha256', self::normalize($code), (string) config('app.key'));
    }

    /** @return array{0:string,1:string} [salt, ciphertext] en base64 */
    public static function seal(array $payload, string $code): array
    {
        $salt = random_bytes(16);
        $key = hash_pbkdf2('sha256', self::normalize($code), $salt, self::ROUNDS, 32, true);
        $iv = random_bytes(12);
        $tag = '';
        $cipher = openssl_encrypt(
            json_encode($payload, JSON_UNESCAPED_UNICODE),
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return [base64_encode($salt), base64_encode($iv . $tag . $cipher)];
    }

    public static function open(string $salt, string $blob, string $code): ?array
    {
        $raw = base64_decode($blob, true);
        $saltRaw = base64_decode($salt, true);
        if ($raw === false || $saltRaw === false || strlen($raw) < 29) {
            return null;
        }

        $key = hash_pbkdf2('sha256', self::normalize($code), $saltRaw, self::ROUNDS, 32, true);
        $plain = openssl_decrypt(
            substr($raw, 28),
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            substr($raw, 0, 12),
            substr($raw, 12, 16)
        );

        if ($plain === false) {
            return null;
        }

        $data = json_decode($plain, true);

        return is_array($data) ? $data : null;
    }
}
