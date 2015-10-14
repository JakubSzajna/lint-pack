<?php

return [
    'all' => [
        'enabled' => true,
        'environment' => 'local',
    ],

    'jshint' => [
        'bin' => base_path('node_modules/.bin/jshint'),
        'jshintrc' => base_path('.jshintrc'),
        'extensions' => [
            'js',
        ],
        'ignores' => [
            '@/bower_components/@',
        ],
        'locations' => [
            base_path('public'),
        ],
    ],

    'phpcpd' => [
        'bin' => base_path('bin/phpcpd'),
        'min_lines' => 5,
        'min_tokens' => 70,
        'extensions' => [
            'php',
        ],
        'ignores' => null,
        'locations' => [
            app_path(),
        ],
    ],

    'phpcs' => [
        'bin' => base_path('bin/phpcs'),
        'warnings' => false,
        'recursion' => true,
        'standard' => 'PSR2',
        'extensions' => [
            'php',
        ],
        'ignores' => null,
        'locations' => [
            app_path(),
        ],
    ],

    'phpmd' => [
        'bin' => base_path('bin/phpmd'),
        'rulesets' => [
            'codesize',
            'controversial',
            'design',
            'naming',
            'unusedcode'
        ],
        'extensions' => [
            'php'
        ],
        'ignores' => [
            'exceptions/Handler\.php'
        ],
        'locations' => [
            app_path(),
        ],
    ],
];