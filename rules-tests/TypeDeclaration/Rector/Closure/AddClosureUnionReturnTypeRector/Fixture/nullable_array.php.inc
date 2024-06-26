<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\Fixture;

final class NullableArray
{
    public function run()
    {
        function () {
            if (rand(0, 1)) {
                return [];
            }

            return null;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\Fixture;

final class NullableArray
{
    public function run()
    {
        function (): ?array {
            if (rand(0, 1)) {
                return [];
            }

            return null;
        };
    }
}

?>
