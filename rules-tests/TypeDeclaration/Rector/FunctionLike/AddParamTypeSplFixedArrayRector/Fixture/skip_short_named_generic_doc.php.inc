<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector\Fixture;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class SkipShortNamedGenericDoc
{
    /**
     * @param Tokens<Token> $tokens
     */
    public function someFunction(Tokens $tokens)
    {
    }
}
