<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedIntConstantReturns
{
    const INT = 0;

    public function getData()
    {
        if (rand(0,1)) {
            return self::INT;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedIntConstantReturns
{
    const INT = 0;

    public function getData(): int
    {
        if (rand(0,1)) {
            return self::INT;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
