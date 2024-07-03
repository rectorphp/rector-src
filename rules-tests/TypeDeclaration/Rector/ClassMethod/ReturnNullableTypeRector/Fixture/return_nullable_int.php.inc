<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\Fixture;

final class ReturnNullableInt
{
    public function run($value)
    {
        if ($value) {
            return null;
        }

        return 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\Fixture;

final class ReturnNullableInt
{
    public function run($value): ?int
    {
        if ($value) {
            return null;
        }

        return 1;
    }
}

?>
