<?php
/*
@copyright

Fleet Manager v5.0.0

Copyright (C) 2017-2020 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
return array(

    'appNameIOS' => array(
        'environment' => 'development',
        'certificate' => '/path/to/certificate.pem',
        'passPhrase' => 'password',
        'service' => 'apns',
    ),
    'appNameAndroid' => array(
        'environment' => 'development',
        'apiKey' => env('server_key'),
        'service' => 'gcm',
    ),

);
