<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

final class SomeClass
{
    public function getFloatItems()
    {
        $floats = [];
        $floats[] = 1.5;
        $floats[] = 2.5;
        return $floats;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

final class SomeClass
{
    /**
     * @return float[]
     */
    public function getFloatItems()
    {
        $floats = [];
        $floats[] = 1.5;
        $floats[] = 2.5;
        return $floats;
    }
}

?>
