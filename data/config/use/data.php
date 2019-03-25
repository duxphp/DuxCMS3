<?php
return [
    'dux.cache_driver' => [
        'files' => [
            'type' => 'files',
            'path' => DATA_PATH . 'cache/',
            'group' => 'tmp',
            'deep' => 0,
        ],
        'redis' => [
            'type' => 'redis',
            'host' => '127.0.0.1',
            'port' => 6379,
            'group' => 0,
        ],
    ],
    'dux.storage_driver' => [
        'files' => [
            'type' => 'files',
            'path' => DATA_PATH . 'storage/',
            'group' => 'common',
            'deep' => 0,
        ],
        'redis' => [
            'type' => 'redis',
            'host' => '127.0.0.1',
            'port' => 6379,
            'group' => 0,
        ],
        'mongoDB' => [
            'type' => 'mongoDB',
            'host' => '127.0.0.1',
            'port' => 27017,
            'group' => 0,
        ],
    ],
    'dux.image_driver' => [
        'gd' => [
            'driver' => 'gd'
        ],
        'imagick' => [
            'driver' => 'imagick'
        ],
    ],
];
