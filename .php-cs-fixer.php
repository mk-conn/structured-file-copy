<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2'                                  => true,
    '@PSR12'                                 => true,
    '@Symfony'                               => true,
    '@PHP82Migration:risky'                  => true,
    'array_syntax'                           => ['syntax' => 'short'],
    'array_indentation'                      => true,
    'blank_line_after_namespace'             => true,
    'concat_space'                           => ['spacing' => 'one'],
    'string_implicit_backslashes'            => ['double_quoted' => 'escape', 'heredoc' => 'escape', 'single_quoted' => 'unescape'],
    'single_line_empty_body'                 => true,
    'fully_qualified_strict_types'           => true,
    'ordered_imports'                        => [
        'imports_order'     => [
            'class', 'function', 'const',
        ],
        'sort_algorithm'    => 'alpha',
    ],
    'braces_position'                       => [
        'classes_opening_brace'             => 'same_line',
        'anonymous_classes_opening_brace'   => 'same_line',
        'anonymous_functions_opening_brace' => 'same_line',
        'control_structures_opening_brace'  => 'same_line',
        'functions_opening_brace'           => 'same_line',
    ],
    'braces'                                => [
        'allow_single_line_anonymous_class_with_empty_body' => true,
        'allow_single_line_closure'                         => true,
    ],

    'blank_line_before_statement' => [
        'statements' => ['if', 'try', 'foreach', 'yield', 'while', 'do', 'for', 'switch', 'break', 'continue', 'throw', 'return', 'exit', 'goto'],
    ],
    'binary_operator_spaces'      => [
        'operators' => [
            '=>' => 'align',
        ],
    ],
    'trailing_comma_in_multiline'                      => ['elements' => ['arrays']],
    'no_whitespace_in_blank_line'                      => true,
    'no_extra_blank_lines'                             => ['tokens' => ['extra']],
    'no_superfluous_phpdoc_tags'                       => ['allow_mixed' => true, 'remove_inheritdoc' => true],
    'cast_spaces'                                      => true,
    'global_namespace_import'                          => ['import_classes' => true, 'import_constants' => false, 'import_functions' => false],
    'php_unit_construct'                               => true,
    'php_unit_dedicate_assert'                         => ['target' => 'newest'],
    'php_unit_expectation'                             => ['target' => 'newest'],
    'php_unit_method_casing'                           => true,
    'php_unit_mock'                                    => ['target' => 'newest'],
    'php_unit_mock_short_will_return'                  => true,
    'php_unit_namespaced'                              => ['target' => 'newest'],
    'php_unit_no_expectation_annotation'               => ['target' => 'newest'],
    'php_unit_set_up_tear_down_visibility'             => true,
    'php_unit_test_case_static_method_calls'           => ['call_type' => 'self'],
    'single_quote'                                     => true,
];

$finder = Finder::create()
                ->in(
                    [
                        __DIR__ . '/src',
                        __DIR__ . '/bootstrap',
                        __DIR__ . '/tests',
                    ]
                )
                ->ignoreDotFiles(true)
                ->name('*.php')
                ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setRules($rules)
    ->setRiskyAllowed(true);
