<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipUnknownConstantIntReturns
{
    public function getData()
    {
        if (rand(0,1)) {
            return self::NOT_DEFINED;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
