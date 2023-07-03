<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipPhpdocTyped
{
    /**
     * @var 0|1
     */
    private $prop1;

    /**
     * @var int
     */
    private $prop2;

    public function resolve1(int $second) {
        return $this->prop1 + $second;
    }

    public function resolve2(int $second) {
        return $this->prop2 + $second;
    }
}
