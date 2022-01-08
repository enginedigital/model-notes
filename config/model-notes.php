<?php

return [
    'note_model' => EngineDigital\Note\Note::class,
    'note_default_type' => 'none',
    // key => 'formatter_class_with_invoke',
    // key => ['formatter', 'method'],
    'note_types' => [
        'none' => null, // dont touch formatted output
        'plain' => 'e', // escape in formatted output
        'html' => null, // dont touch formatted output
        'markdown' => [\Illuminate\Support\Str::class, 'markdown'],
        'json' => 'json_decode', // treat the note content as JSON
    ],
    'model_primary_key_attribute' => 'model_id',
    'encrypt_notes' => false,
    'tenant_model' => null, // App\Models\Company::class
    'tenant_resolver' => null, // a class that uses `__invoke` or a container function to get the id of the current tenant
    'author_model' => null, // App\Models\User::class
    'author_resolver' => null, // a class that uses `__invoke` or a container function to get the id of the current user
    'cache_time' => null, // cache time in seconds
];
