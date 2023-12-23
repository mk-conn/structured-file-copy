<?php
declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2'                       => true,
    'array_syntax'                => ['syntax' => 'short'],
    'array_indentation'           => true,
    'blank_line_after_namespace'  => true,
    'braces_position'             => [
        'classes_opening_brace'   => 'same_line',
        'functions_opening_brace' => 'same_line',

    ],
    'blank_line_before_statement' => [
        'statements' => ['if', 'try', 'foreach', 'return', 'yield']
    ],
    'cast_spaces'                 => true
];

$finder = Finder::create()
                ->in(
                    [
                        __DIR__ . '/src',
                        __DIR__ . '/bootstrap',
                        __DIR__ . '/tests'
                    ]
                )
                ->ignoreDotFiles(true)
                ->name('*.php')
                ->ignoreVCS(true);

return (new Config)
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setRules($rules);
