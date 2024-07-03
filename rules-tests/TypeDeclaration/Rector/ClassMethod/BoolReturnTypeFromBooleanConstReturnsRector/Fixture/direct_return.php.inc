<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector\Fixture;

final class DirectReturn
{
    public function resolve($first)
    {
        if ($first) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector\Fixture;

final class DirectReturn
{
    public function resolve($first): bool
    {
        if ($first) {
            return false;
        }

        return true;
    }
}

?>
