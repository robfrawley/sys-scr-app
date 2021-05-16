<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<HEADER
This file is part of the `src-run/src-run-web` project.

(c) Rob Frawley 2nd <rmf@src.run>

For the full copyright and license information, please view the LICENSE.md
file that was distributed with this source code.
HEADER;

return (new Config())
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setHideProgress(false)
    ->setLineEnding("\n")
    ->setIndent('    ')
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder(
        (new Finder())
            ->in(__DIR__)
            ->exclude('var')
    )
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHPUnit57Migration:risky' => true,
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_like'
        ],
        'array_indentation' => true,
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'binary_operator_spaces' => [
            'align_double_arrow' => false,
            'align_equals' => false,
        ],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'escape_implicit_backslashes' => true,
        'explicit_indirect_variable' => true,
        'final_internal_class' => true,
        'function_typehint_space' => true,
        'hash_to_slash_comment' => true,
        'header_comment' => [
            'header' => $header, 'separate' => 'both'
        ],
        'heredoc_to_nowdoc' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'lowercase_cast' => true,
        'mb_str_functions' => true,
        'method_separation' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_extra_consecutive_blank_lines' => [
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'square_brace_block',
            'throw',
            'use',
        ],
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_class_elements' => ['order' => [
            'use_trait',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public',
            'method_protected',
            'method_private'
        ],],
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'php_unit_no_expectation_annotation' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'psr4' => true,
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'single_quote' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
;
