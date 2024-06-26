<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureNeverReturnTypeRector\Fixture;

use Exception;

final class Fixture
{
    public function run()
    {
        function () {
            throw new Exception('test');
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureNeverReturnTypeRector\Fixture;

use Exception;

final class Fixture
{
    public function run()
    {
        function (): never {
            throw new Exception('test');
        };
    }
}

?>
