<?php

namespace EngineDigital\Note;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptNoteCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): string
    {
        $enabled = config('model-notes.encrypt_notes');

        try {
            return $enabled ? Crypt::decryptString($value) : $value;
        } catch (DecryptException $e) {
            throw $e;
        }
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        $enabled = config('model-notes.encrypt_notes');

        return $enabled ? Crypt::encryptString($value) : $value;
    }
}
