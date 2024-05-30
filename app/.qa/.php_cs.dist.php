<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use Symfony\Component\Finder\Finder;

$finder = Finder::create()
    ->in('./public')
    ->in('./src')
    ->in('./tests')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$nonRiskyRules = [
    // Place here only **Non-risky rules**. Please respect the alphabetical order
    '@PHP70Migration' => true,
    '@PHP71Migration' => true,
    '@PHP73Migration' => true,
    '@PHP74Migration' => true,
    '@PHP80Migration' => true,
    '@PHP81Migration' => true,
    '@PHP82Migration' => true,
    '@PER' => true,
    '@Symfony' => true,
    'align_multiline_comment' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_before_statement' => true,
    'binary_operator_spaces' => ['operators' => ['=>' => 'align_single_space_minimal']],
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'echo_tag_syntax' => ['format' => 'long'],
    'fully_qualified_strict_types' => true,
    'general_phpdoc_annotation_remove' => ['annotations' => ['author', 'package', 'subpackage', 'version',]],
    'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
    'header_comment' => ['header' => ''],
    'heredoc_to_nowdoc' => true,
    'list_syntax' => ['syntax' => 'short'],
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    'method_chaining_indentation' => true,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,
    'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
    'no_null_property_initialization' => true,
    'no_superfluous_elseif' => true,
    'no_unneeded_curly_braces' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => ['imports_order' => ['class', 'const', 'function'],],
    'php_unit_method_casing' => ['case' => 'snake_case'],
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_line_span' => ['const' => 'single', 'property' => 'single', 'method' => 'single'],
    'phpdoc_order' => true,
    'phpdoc_types_order' => true,
    'self_static_accessor' => true,
    'semicolon_after_instruction' => true,
    'single_line_comment_style' => true,
    'ternary_to_null_coalescing' => true,
    'use_arrow_functions' => false,
    'visibility_required' => true,
    'yoda_style' => true,
];

$riskyRules = [
    // Place here only **Risky rules**. Please respect the alphabetical order
    '@PHP70Migration:risky' => true,
    '@PHP71Migration:risky' => true,
    '@PHP74Migration:risky' => true,
    '@PHP80Migration:risky' => true,
    '@PHPUnit84Migration:risky' => true,
    '@PER:risky' => true,
    '@Symfony:risky' => true,
    'date_time_immutable' => true,
    'is_null' => true,
    'logical_operators' => true,
    'mb_str_functions' => true,
    'native_constant_invocation' => true,
    'native_function_invocation' => true,
    'no_unneeded_final_method' => true,
    'no_unreachable_default_argument_value' => true,
    'php_unit_strict' => false,
    'php_unit_test_case_static_method_calls' => true,
    'static_lambda' => true,
    'strict_comparison' => true,
    'strict_param' => true,
];

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules(array_merge($nonRiskyRules, $riskyRules))
    ->setFinder($finder);
