<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class WithEmpty
{
    public function resolve($a)
    {
        return empty($a['test']);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class WithEmpty
{
    public function resolve($a): bool
    {
        return empty($a['test']);
    }
}

?>
