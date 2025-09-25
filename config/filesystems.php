<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Disco por defecto. Puedes cambiarlo vía .env con FILESYSTEM_DISK.
    | Para tu caso puedes dejar "local" sin problema.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Configura todos los discos que necesites. Usamos "public" para archivos
    | servibles (como el avatar) y "local" para privados.
    |
    */

    'disks' => [

        // Archivos privados (no públicos)
        'local' => [
            'driver'  => 'local',
            'root'    => storage_path('app/private'),
            // Estas claves son opcionales según tu versión de Laravel;
            // las mantengo porque las traías ya en tu archivo:
            'serve'   => true,
            'throw'   => false,
            'report'  => false,
        ],

        // Archivos públicos (se guardan en storage/app/public)
        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            // Esta URL funciona cuando el symlink public/storage → storage/app/public está activo.
            // Además, en la solución te di una RUTA /media que sirve directo desde este disco,
            // así que aunque el symlink no funcione, todo seguirá cargando.
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw'      => false,
            'report'     => false,
        ],

        // Ejemplo S3 (si algún día lo usas)
        's3' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_URL'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw'                   => false,
            'report'                  => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Enlaces simbólicos creados por `php artisan storage:link`.
    | Deja esto así para que /storage apunte a storage/app/public.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
