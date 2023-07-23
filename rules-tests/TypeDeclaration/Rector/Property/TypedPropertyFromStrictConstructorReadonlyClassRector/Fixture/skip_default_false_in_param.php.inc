<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

final class SkipDefaultFalseInParam
{
    private $someString = false;

    public function __construct(string $someString)
    {
        if (mt_rand(0, 1)) {
            $this->someString = $someString;
        }
    }
}
