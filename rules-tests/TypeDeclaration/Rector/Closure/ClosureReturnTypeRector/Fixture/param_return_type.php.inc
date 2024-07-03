<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class ParamReturnType
{
    public function run()
    {
        $result = function (int $number) {
            return $number;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class ParamReturnType
{
    public function run()
    {
        $result = function (int $number): int {
            return $number;
        };
    }
}

?>
