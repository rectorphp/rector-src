<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SomeClass
{
    private int $number;

    public function setValue($number)
    {
        $this->number = $number;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SomeClass
{
    private int $number;

    public function setValue(int $number)
    {
        $this->number = $number;
    }
}

?>
