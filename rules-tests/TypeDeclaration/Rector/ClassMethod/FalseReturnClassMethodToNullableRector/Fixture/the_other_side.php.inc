<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FalseReturnClassMethodToNullableRector\Fixture;

final class TheOtherSide
{
    /**
     * @return false|int
     */
    public function run(int $number)
    {
        if ($number !== 10) {
            return $number;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FalseReturnClassMethodToNullableRector\Fixture;

final class TheOtherSide
{
    public function run(int $number): ?int
    {
        if ($number !== 10) {
            return $number;
        }

        return null;
    }
}

?>
