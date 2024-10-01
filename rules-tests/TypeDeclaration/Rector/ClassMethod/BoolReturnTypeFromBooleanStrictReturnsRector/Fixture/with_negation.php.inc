<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class WithNegation
{
    public function resolve($a)
    {
        return ! $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class WithNegation
{
    public function resolve($a): bool
    {
        return ! $a;
    }
}

?>
