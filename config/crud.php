<?php

/**
 * Ibex CRUD generator — Services-first + Flux + modules.
 *
 * @see resources/stubs/crud/README.md
 */
return [
    'stub_path' => resource_path('stubs/crud'),

    'layout' => 'layouts.app',

    'model' => [
        'namespace' => 'App\\Models',
        'unwantedColumns' => [
            'id',
            'uuid',
            'ulid',
            'password',
            'email_verified_at',
            'remember_token',
            'created_at',
            'updated_at',
            'deleted_at',
            'creado_por',
            'actualizado_por',
        ],
    ],

    'controller' => [
        'namespace' => 'App\\Http\\Controllers',
        'apiNamespace' => 'App\\Http\\Controllers\\Api',
    ],

    'resources' => [
        'namespace' => 'App\\Http\\Resources',
    ],

    'livewire' => [
        'namespace' => 'App\\Livewire',
    ],

    'request' => [
        'namespace' => 'App\\Http\\Requests',
    ],

    'service' => [
        'namespace' => 'App\\Services',
    ],
];
