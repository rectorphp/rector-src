<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

use PhpCsFixer\Tokenizer\Token;

class SkipNotIdenticalReturnFalseThenReturnInArray
{
    private const POSSIBLE_DELIMITERS = ['a'];

    public function run($firstChar, $lastChar)
    {
        if ($firstChar !== $lastChar) {
            return false;
        }

        return in_array($firstChar, self::POSSIBLE_DELIMITERS, true);
    }
}