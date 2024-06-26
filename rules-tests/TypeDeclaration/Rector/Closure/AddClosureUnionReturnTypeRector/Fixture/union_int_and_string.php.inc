<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\Fixture;

final class UnionIntAndString
{
    public function run()
    {
        function () {
            if (rand(0, 1)) {
                return 1;
            }

            return 'one';
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\Fixture;

final class UnionIntAndString
{
    public function run()
    {
        function (): int|string {
            if (rand(0, 1)) {
                return 1;
            }

            return 'one';
        };
    }
}

?>
