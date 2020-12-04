<?php

namespace Laramore\Fields;

use Illuminate\Support\Facades\Schema;

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
        'type' => 'text',
        'property_keys' => [
            'nullable', 'default',
        ],
    ],
    Image::class => [
        'type' => 'text',
        'property_keys' => [
            'nullable', 'default',
        ],
    ],
    Social::class => [
        'type' => 'text',
        'property_keys' => [
            'nullable', 'default',
        ],
    ],
    
];
