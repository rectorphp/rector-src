<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedArrayReturns
{
    public function getData()
    {
        if (rand(0,1)) {
            return [];
        }

        return $this->getArray();
    }

    public function getArray(): array
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedArrayReturns
{
    public function getData(): array
    {
        if (rand(0,1)) {
            return [];
        }

        return $this->getArray();
    }

    public function getArray(): array
    {

    }
}

?>
