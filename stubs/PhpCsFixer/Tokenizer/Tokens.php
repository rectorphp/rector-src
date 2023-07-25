<?php

declare(strict_types=1);

namespace PhpCsFixer\Tokenizer;

if (class_exists('PhpCsFixer\Tokenizer\Tokens')) {
    return;
}

final class Tokens extends \SplFixedArray
{
    public const BLOCK_TYPE_PARENTHESIS_BRACE = 1;
    public const BLOCK_TYPE_CURLY_BRACE = 2;
    public const BLOCK_TYPE_INDEX_SQUARE_BRACE = 3;
    public const BLOCK_TYPE_ARRAY_SQUARE_BRACE = 4;
    public const BLOCK_TYPE_DYNAMIC_PROP_BRACE = 5;
    public const BLOCK_TYPE_DYNAMIC_VAR_BRACE = 6;
    public const BLOCK_TYPE_ARRAY_INDEX_CURLY_BRACE = 7;
    public const BLOCK_TYPE_GROUP_IMPORT_BRACE = 8;
    public const BLOCK_TYPE_DESTRUCTURING_SQUARE_BRACE = 9;
    public const BLOCK_TYPE_BRACE_CLASS_INSTANTIATION = 10;
    public const BLOCK_TYPE_ATTRIBUTE = 11;
    public const BLOCK_TYPE_DISJUNCTIVE_NORMAL_FORM_TYPE_PARENTHESIS = 12;
    public const BLOCK_TYPE_DYNAMIC_CLASS_CONSTANT_FETCH_CURLY_BRACE = 13;
}
