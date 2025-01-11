<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source\ByRefTrait;

final class SkipByRefAssignOnTrait
{
    use ByRefTrait;

    private $str;

    public function __construct(string $str)
    {
        $this->str = $str;
    }
}
