<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromReturnCastRector\Fixture;

final class CastStringReturn
{
    public function run()
    {
        function ($param) {
            return (string) $param;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromReturnCastRector\Fixture;

final class CastStringReturn
{
    public function run()
    {
        function ($param): string {
            return (string) $param;
        };
    }
}

?>
