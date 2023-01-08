<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

final class SkipDefaultFalseInParam
{
    private $nullableString = false;

    public function __construct(string $nullableString)
    {
        if (mt_rand(0, 1)) {
            $this->nullableString = $nullableString;
        }
    }
}
