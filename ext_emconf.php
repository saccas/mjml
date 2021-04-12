<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Mjml',
    'description' => 'Mjml view using mjml over npm',
    'category' => 'misc',
    'shy' => 0,
    'version' => '2.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'author' => '',
    'author_email' => '',
    'author_company' => '',
    'autoload' =>
        [
            'psr-4' => ['Saccas\\Mjml\\' => 'Classes']
        ],
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'typo3' => '9.5.17-10.9.99',
            'extbase' => '',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    '_md5_values_when_last_written' => 'a:0:{}',
    'suggests' => [
    ],
];
