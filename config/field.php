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

    'configurations' => [
        File::class => [
            'type' => 'file',
            'max_size' => 2048,
            'mime_types' => [
                'pdf', 'jpg', 'jpeg', 'png', 'docx', 'doc', 'odp', 'zip', 'txt',
            ],
            'root_path' => \storage_path(''),
            'proxy' => [
                'configurations' => [
                    'save' => [],
                    'delete' => [],
                ],
            ],
        ],
        Image::class => [
            'type' => 'image',
            'max_size' => 2048,
            'mime_types' => [
                'jpg', 'jpeg', 'png',
            ],
            'root_path' => \public_path(''),
            'proxy' => [
                'configurations' => [
                    'save' => [],
                    'delete' => [],
                ],
            ],
        ],
        Url::class => [
            'type' => 'url',
            'max_length' => Schema::getFacadeRoot()::$defaultStringLength,
            'secured' => true,
            'proxy' => [
                'configurations' => [
                    'dry' => [
                        'static' => true,
                        'allow_multi' => false,
                    ],
                    'hydrate' => [
                        'static' => true,
                        'allow_multi' => false,
                    ],
                    'fix' => [],
                ],
            ],
            'patterns' => [
                'identifier' => '/^\S+$/',
                'protocol' => 'http://',
                'secured_protocol' => 'https://',
                'uri' => '/^\S+:\/{0,2}\S+$/',
                'flags' => null,
            ]
        ],
        Social::class => [
            'type' => 'url',
            'max_length' => Schema::getFacadeRoot()::$defaultStringLength,
            'proxy' => [
                'configurations' => [
                    'fix' => [],
                ],
            ],
            'patterns' => [
                'identifier' => '/^\S+$/',
                'protocol' => '/^https?:\/{0,2}$/',
                'secured_protocol' => '/^https:\/{0,2}$/',
                'uri' => '/^\S+:\/{0,2}\S+$/',
                'separator' => '',
                'flags' => null,
            ],
            'socials' => [ # Based on https://github.com/lorey/social-media-profiles-regexs.
                'twitter' => '(?:https?:)?\/\/(?:[A-z]+\.)?twitter\.com\/@?(?P<username>[A-z0-9_]+)\/?',
                'github' => '(?:https?:)?\/\/(?:www\.)?github\.com\/(?P<login>[A-z0-9_-]+)\/?',
                'linkedin' => '(?:https?:)?\/\/(?:[\w]+\.)?linkedin\.com\/in\/(?P<permalink>[\w\-\_À-ÿ%]+)\/?',
                'facbeook' => '[((?:https?:)?\/\/(?:www\.)?(?:facebook|fb)\.com\/(?P<profile>(?![A-z]+\.php)(?!marketplace|gaming|watch|me|messages|help|search|groups)[A-z0-9_\-\.]+)\/?)((?:https?:)?\/\/(?:www\.)facebook.com/(?:profile.php\?id=)?(?P<id>[0-9]+))]',
                'profil' => '(?:https?:)?\/\/(?:www\.)?(?:instagram\.com|instagr\.am)\/(?P<username>[A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)',
                'phone' => '(?:tel|phone|mobile):(?P<number>\+?[0-9. -]+)',
                'youtube' => '(?:https?:)?\/\/(?:[A-z]+\.)?youtube.com\/user\/(?P<username>[A-z0-9]+)\/?',
                'reddit' => '(?:https?:)?\/\/(?:[a-z]+\.)?reddit\.com\/(?:u(?:ser)?)\/(?P<username>[A-z0-9\-\_]*)\/?',
                'snapchat' => '(?:https?:)?\/\/(?:www\.)?snapchat\.com\/add\/(?P<username>[A-z0-9\.\_\-]+)\/?'
            ]
        ],
    ],
    
];
