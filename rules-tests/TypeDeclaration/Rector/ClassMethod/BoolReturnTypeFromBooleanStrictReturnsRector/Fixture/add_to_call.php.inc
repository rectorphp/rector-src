<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class AddToCall
{
    public function resolve($first, $second)
    {
        return $first >= $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class AddToCall
{
    public function resolve($first, $second): bool
    {
        return $first >= $second;
    }
}

?>
