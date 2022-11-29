<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source\SomeSkipMeTrait;

final class SkipIfTraitHasSameProperty
{
    use SomeSkipMeTrait;

    private $skipMe;

    public function __construct(string $nullableString = null)
    {
        $this->skipMe = $nullableString;
    }
}
