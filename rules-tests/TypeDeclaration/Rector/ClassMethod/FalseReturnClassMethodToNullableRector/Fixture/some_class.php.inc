<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FalseReturnClassMethodToNullableRector\Fixture;

class SomeClass
{
    /**
     * @return false|int
     */
    public function run(int $number)
    {
        if ($number === 10) {
            return false;
        }

        return $number;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FalseReturnClassMethodToNullableRector\Fixture;

class SomeClass
{
    public function run(int $number): ?int
    {
        if ($number === 10) {
            return null;
        }

        return $number;
    }
}

?>
