<?php

declare(strict_types=1);

namespace PhpCsFixer\Tokenizer;

use SplFixedArray;

if (class_exists('PhpCsFixer\Tokenizer\Tokens')) {
    return;
}

/**
 * @extends SplFixedArray<Token>
 */
class Tokens extends SplFixedArray
{
}
