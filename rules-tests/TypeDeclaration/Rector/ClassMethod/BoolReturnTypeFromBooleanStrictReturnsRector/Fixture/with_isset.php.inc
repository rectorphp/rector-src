<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class WithIsset
{
    public function resolve($a)
    {
        return isset($a['test']);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class WithIsset
{
    public function resolve($a): bool
    {
        return isset($a['test']);
    }
}

?>
