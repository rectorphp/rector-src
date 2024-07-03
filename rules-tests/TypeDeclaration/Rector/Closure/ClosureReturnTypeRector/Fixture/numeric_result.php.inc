<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class NumericResult
{
    public function run()
    {
        $result = function (int $first, int $second) {
            return $first - $second;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class NumericResult
{
    public function run()
    {
        $result = function (int $first, int $second): int {
            return $first - $second;
        };
    }
}

?>
