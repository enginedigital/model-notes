<?php

return [
    'note_model' => EngineDigital\Note\Note::class,
    'note_default_type' => 'plain',
    'note_types' => [
        'plain',
        'html',
        'markdown',
        'json',
    ],
    'model_primary_key_attribute' => 'model_id',
    'encrypt_notes' => false,
    'tenant_model' => null, // App\Models\Company::class
    'tenant_resolver' => null, // a class that uses `__invoke` to get the id of the current tenant
];
