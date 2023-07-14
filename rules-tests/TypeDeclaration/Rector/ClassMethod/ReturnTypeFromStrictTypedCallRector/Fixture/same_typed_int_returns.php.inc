<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedIntReturns
{
    public function getData()
    {
        if (rand(0,1)) {
            return 0;
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

final class SameTypedIntReturns
{
    public function getData(): int
    {
        if (rand(0,1)) {
            return 0;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
