<?php
/*
@copyright

Fleet Manager v5.0.0

Copyright (C) 2017-2020 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
return array(

    /*
    |--------------------------------------------------------------------------
    | Enable Backup
    |--------------------------------------------------------------------------
    |
    | Enable database backup.
    |
     */
    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    | A database backup path, absolute path, or path relative from public
    | directory, a trailing slash is required.
    |
     */
    'path' => storage_path(),

    /*
    |--------------------------------------------------------------------------
    | Filename
    |--------------------------------------------------------------------------
    |
    | A database export filename to use when exporting databases.
    |
     */
    'filename' => 'backup-' . date('Ymd-His'),

    /*
    |--------------------------------------------------------------------------
    | Enable Compression
    |--------------------------------------------------------------------------
    |
    | Enable backup compression using gzip. Requires gzencode/gzdecode.
    |
     */
    'compress' => false,

    /*
    |--------------------------------------------------------------------------
    | Database Engine Processors
    |--------------------------------------------------------------------------
    |
    | Set the database engines processor location, trailing slash is required.
    |
     */
    'processors' => array(
        'mysql' => array(
            'export' => '/usr/bin/',
            'restore' => '/usr/bin/',
        ),
        'pqsql' => array(
            'export' => '/usr/bin/',
            'restore' => '/usr/bin/',
        ),
        'sqlite' => array(
            'export' => null,
            'restore' => null,
        ),
        'sqlsrv' => array(
            'export' => '/usr/bin/',
            'restore' => '/usr/bin/',
        ),
    ),

);
