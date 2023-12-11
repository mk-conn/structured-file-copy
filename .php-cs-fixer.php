<?php
declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2'                       => true,
    'array_syntax'                => ['syntax' => 'short'],
    'array_indentation'           => true,
    'blank_line_after_namespace'  => true,
    'blank_line_before_statement' => [
        'statements' => ['if', 'try', 'foreach', 'return', 'yield']
    ],
    'cast_spaces'                 => true
];

$finder = Finder::create()
    ->in(
        [
            __DIR__ . '/src'
        ]
    )
    ->ignoreDotFiles(true)
    ->name('*.php')
    ->ignoreVCS(true);

return (new Config)
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setRules($rules);
