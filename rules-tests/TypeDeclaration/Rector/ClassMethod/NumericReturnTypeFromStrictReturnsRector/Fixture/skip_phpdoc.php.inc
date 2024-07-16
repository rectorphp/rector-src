<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictReturnsRector\Fixture;

final class SkipPhpDoc
{
    /**
     * @var int
     */
    private $x;

    /**
     * @return int
     */
    public function resolve()
    {
        return $this->x;
    }

}
