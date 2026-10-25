<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class YieldInsideInnerFunction
{
    public function getValues()
    {
        $result = function () {
            yield [1000];
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class YieldInsideInnerFunction
{
    public function getValues(): void
    {
        $result = function () {
            yield [1000];
        };
    }
}

?>
