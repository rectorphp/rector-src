<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class ReturnDirectArray
{
    public function run()
    {
        function () {
            return [1, 2, 3];
        };

        function () {
            $anArray = [4, 5, 6];
            return $anArray;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class ReturnDirectArray
{
    public function run()
    {
        function (): array {
            return [1, 2, 3];
        };

        function (): array {
            $anArray = [4, 5, 6];
            return $anArray;
        };
    }
}

?>
