<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Caretaker Instance',
    'description' => 'Client for caretaker observation system',
    'category' => 'misc',
    'author' => 'Martin Ficzel,Thomas Hempel,Christopher Hlubek,Tobias Liebig',
    'author_email' => 'ficzel@work.de,hempel@work.de,hlubek@networkteam.com,typo3@etobi.de',
    'shy' => '',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'author_company' => '',
    'version' => '0.7.7',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.1-8.7.99',
                    'php' => '5.6.0-0.0.0',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                ],
        ],
    'psr-4' => [
        'Caretaker\\CaretakerInstance\\' => 'Classes',
    ],
];
