<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class MultiScalar
{
    public function run($value)
    {
        if ($value) {
            return false;
        }

        return substr('warning', 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class MultiScalar
{
    public function run($value): bool|string
    {
        if ($value) {
            return false;
        }

        return substr('warning', 1);
    }
}

?>
