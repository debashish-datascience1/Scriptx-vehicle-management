<?php
/*
@copyright

Fleet Manager v5.0.0

Copyright (C) 2017-2020 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
return [

    'default' => 'local',

    'cloud' => 's3',

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'views' => [
            'driver' => 'local',
            'root' => base_path('resources/lang'),
        ],

        'public_uploads' => [
            'driver' => 'local',
            'root' => 'public/uploads',
        ],

        'public_img' => [
            'driver' => 'local',
            'root' => 'img',
        ],

        'public_files' => [
            'driver' => 'local',
            'root' => 'files',
        ],

        'public_files2' => [
            'driver' => 'local',
            'root' => '../files',
        ],

        'public_img2' => [
            'driver' => 'local',
            'root' => '../img',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

    ],

];
