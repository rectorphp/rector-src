<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IncludeUnaryMinus
{
    public function resolve()
    {
        return -1000;
    }

    public function next()
    {
        return +1000;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class IncludeUnaryMinus
{
    public function resolve(): int
    {
        return -1000;
    }

    public function next()
    {
        return +1000;
    }
}

?>
