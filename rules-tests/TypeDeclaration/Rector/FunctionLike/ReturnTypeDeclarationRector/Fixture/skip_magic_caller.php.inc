<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use Rector\Core\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Input\StringInput;

final class SkipMagicCaller
{
    public function run(): array
    {
        $privatesCaller = new PrivatesAccessor();
        return $privatesCaller->callPrivateMethod(new StringInput(''), 'tokenize', []);
    }
}
