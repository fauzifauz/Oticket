<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Encryption\Encrypter;

class DeterministicEncrypted implements CastsAttributes
{
    protected $encrypter;

    public function __construct()
    {
        // Use a fixed IV derived from APP_KEY for deterministic encryption
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        
        // We use a fixed 16-byte IV for AES-128/256-CBC
        // This makes the encryption predictable/deterministic for the same key
        $fixedIv = substr(hash('sha256', 'OTicket-Deterministic-IV'), 0, 16);
        $this->encrypter = new Encrypter($key, config('app.cipher'));
    }

    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        
        $iv = substr(hash('sha256', 'OTicket-Deterministic-IV'), 0, 16);
        $decrypted = openssl_decrypt($value, config('app.cipher'), $key, 0, $iv);
        
        if ($decrypted === false) {
            // Might be plain text or old encryption format
            return $value;
        }

        try {
            return unserialize($decrypted);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Prepare the given value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        // For Deterministic Encryption, we use a custom approach
        // Standard Crypt uses random IV. We need standard Crypt for storage 
        // but a custom lookup method.
        // Actually, if we use standard Crypt::encryptString, we can't search.
        // So we MUST use a custom encrypter with fixed IV here.
        
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        
        // Deterministic encrypt:
        $iv = substr(hash('sha256', 'OTicket-Deterministic-IV'), 0, 16);
        $value = openssl_encrypt(serialize($value), config('app.cipher'), $key, 0, $iv);
        
        if ($value === false) {
            throw new \RuntimeException('Could not encrypt the data.');
        }

        return $value;
    }
    
    /**
     * Helper to encrypt a value for searching
     */
    public static function encryptForSearch($value)
    {
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        $iv = substr(hash('sha256', 'OTicket-Deterministic-IV'), 0, 16);
        return openssl_encrypt(serialize($value), config('app.cipher'), $key, 0, $iv);
    }
}
