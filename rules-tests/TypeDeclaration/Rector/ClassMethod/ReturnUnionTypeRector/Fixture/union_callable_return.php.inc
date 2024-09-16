<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class UnionCallableReturn
{
    public function run(callable $a)
    {
        if (rand(0, 1)) {
            return $a;
        }

        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class UnionCallableReturn
{
    public function run(callable $a): callable|array
    {
        if (rand(0, 1)) {
            return $a;
        }

        return [];
    }
}

?>
