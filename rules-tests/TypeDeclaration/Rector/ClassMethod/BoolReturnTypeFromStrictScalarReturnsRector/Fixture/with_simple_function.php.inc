<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class WithSimpleFunction
{
    public function resolve($first, $second)
    {
        if ($first) {
            return false;
        }

        if ($second) {
            return $first > $second;
        }

        return is_bool($second);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class WithSimpleFunction
{
    public function resolve($first, $second): bool
    {
        if ($first) {
            return false;
        }

        if ($second) {
            return $first > $second;
        }

        return is_bool($second);
    }
}

?>
