<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector\Fixture;

use PhpCsFixer\Tokenizer\Tokens;

final class SomeFixture
{
    public function someFunction(Tokens $tokens)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector\Fixture;

use PhpCsFixer\Tokenizer\Tokens;

final class SomeFixture
{
    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    public function someFunction(Tokens $tokens)
    {
    }
}

?>
