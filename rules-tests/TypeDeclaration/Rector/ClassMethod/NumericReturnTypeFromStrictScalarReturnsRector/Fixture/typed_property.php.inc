<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class TypedProperty
{
    private int $prop1;

    public function resolve1(int $second) {
        return $this->prop1 + $second;
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class TypedProperty
{
    private int $prop1;

    public function resolve1(int $second): int {
        return $this->prop1 + $second;
    }
}
?>
