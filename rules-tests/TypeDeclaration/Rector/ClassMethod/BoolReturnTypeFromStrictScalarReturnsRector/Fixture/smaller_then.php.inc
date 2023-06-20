<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SmallerThen
{
    public function resolve($first, $second)
    {
        return $first >= $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SmallerThen
{
    public function resolve($first, $second): bool
    {
        return $first >= $second;
    }
}

?>
