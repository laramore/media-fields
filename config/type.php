<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default text types
    |--------------------------------------------------------------------------
    |
    | This option defines the default text types used by fields.
    |
    */

    'configurations' => [
        'url' => [
            'native' => 'url',
            'parent' => 'uri',
        ],
        'file' => [
            'native' => 'file',
            'parent' => 'pattern',
            'factory_name' => 'image',
        ],
    ],

];
