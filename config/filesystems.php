<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images'),
            'url' => env('API_URL').'/storage/images',
            'visibility' => 'public',
            'throw' => false,
        ],
        'companies' => [
            'driver' => 'local',
            'root' => storage_path('app/public/companies'),
            'url' => env('API_URL').'/storage/companies',
            'visibility' => 'public',
            'throw' => false,
        ],
        'companies-docs' => [
            'driver' => 'local',
            'root' => storage_path('app/private/companies/docs'),
            'url' => env('API_URL').'/storage/companies/docs',
            'visibility' => 'private',
            'throw' => false,
        ],
        'videos' => [
            'driver' => 'local',
            'root' => storage_path('app/public/videos'),
            'url' => env('API_URL').'/storage/videos',
            'visibility' => 'public',
            'throw' => false,
        ],
        'services' => [
            'driver' => 'local',
            'root' => storage_path('app/public/services'),
            'url' => env('API_URL').'/storage/services',
            'visibility' => 'public',
            'throw' => false,
        ],
        'categories' => [
            'driver' => 'local',
            'root' => storage_path('app/public/categories'),
            'url' => env('API_URL').'/storage/categories',
            'visibility' => 'public',
            'throw' => false,
        ],
        'users' => [
            'driver' => 'local',
            'root' => storage_path('app/public/users'),
            'url' => env('API_URL').'/storage/users',
            'visibility' => 'public',
            'throw' => false,
        ],
        'projects' => [
            'driver' => 'local',
            'root' => storage_path('app/public/projects'),
            'url' => env('API_URL').'/storage/projects',
            'visibility' => 'public',
            'throw' => false,
        ],
        'quotes' => [
            'driver' => 'local',
            'root' => storage_path('app/public/quotes'),
            'url' => env('API_URL').'/storage/quotes',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
