<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureVoidReturnTypeWhereNoReturnRector\Fixture;

final class ReturnInsideInnerFunction
{
    public function getValues()
    {
        $result = function () {
            $value = 1000;
            if ($value) {
                return;
            }

            return 10;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureVoidReturnTypeWhereNoReturnRector\Fixture;

final class ReturnInsideInnerFunction
{
    public function getValues()
    {
        $result = function () {
            $value = 1000;
            if ($value) {
                return;
            }

            return 10;
        };
    }
}

?>
