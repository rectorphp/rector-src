<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipPhpdocTypedProps
{
    /**
     * @var 0|1
     */
    private $prop1;

    public function resolve1(int $second) {
        return $this->prop1 + $second;
    }
}
