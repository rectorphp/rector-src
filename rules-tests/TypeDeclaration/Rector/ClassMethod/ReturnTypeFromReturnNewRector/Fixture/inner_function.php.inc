<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use stdClass;

final class InnerFunction
{
    public function __construct()
    {
        $a = function () {
            return new stdClass;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use stdClass;

final class InnerFunction
{
    public function __construct()
    {
        $a = function () {
            return new stdClass;
        };
    }
}

?>
