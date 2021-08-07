<?php

namespace Laramore\Fields;

return [

    /*
    |--------------------------------------------------------------------------
    | Default text fields
    |--------------------------------------------------------------------------
    |
    | This option defines the default text fields.
    |
    */

    File::class => [
        'formater' => 'file',
        'parameters' => [
            base_path('vendor/laramore/media-fields/assets'), '/tmp'
        ],
    ],
    Image::class => [
        'formater' => 'image',
    ],
    Social::class => [
        
    ],
    
];
