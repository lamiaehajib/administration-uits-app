<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),
    
    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
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
        
        // ** هذا هو المكان الصحيح لإعدادات Google Drive **
        'google' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            
            // نستخدم Folder ID لفرض المسار، ونتجاهل 'folder' و 'root'
            'folderId' => '1BapCOVBd-UNDC3CzbkcTjrmas7jBURGs', 
            'root' => '',    
            'folder' => '',  
        ],
        // **********************************************************

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];