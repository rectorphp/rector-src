<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class InClosure
{
    public function resolve($first, $second)
    {
        function () use ($first, $second) {
            return $first == $second;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class InClosure
{
    public function resolve($first, $second)
    {
        function () use ($first, $second): bool {
            return $first == $second;
        };
    }
}

?>
