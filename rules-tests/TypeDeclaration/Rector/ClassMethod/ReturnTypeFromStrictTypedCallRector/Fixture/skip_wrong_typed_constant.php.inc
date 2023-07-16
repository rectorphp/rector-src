<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipWrongTypedConstant
{
    const STRING = 'hello';

    public function getData()
    {
        if (rand(0,1)) {
            return self::STRING;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
