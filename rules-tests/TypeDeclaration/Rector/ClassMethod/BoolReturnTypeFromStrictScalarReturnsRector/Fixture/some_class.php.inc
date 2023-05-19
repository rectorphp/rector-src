<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

class SomeClass
{
    public function resolve($first, $second)
    {
        if ($first) {
            return false;
        }

        return $first > $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

class SomeClass
{
    public function resolve($first, $second): bool
    {
        if ($first) {
            return false;
        }

        return $first > $second;
    }
}

?>
