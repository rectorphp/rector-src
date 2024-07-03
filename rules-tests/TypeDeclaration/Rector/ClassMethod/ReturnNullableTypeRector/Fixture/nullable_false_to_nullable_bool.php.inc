<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\Fixture;

final class NullableFalseToNullableBool
{
    public function run($value)
    {
        if ($value) {
            return null;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\Fixture;

final class NullableFalseToNullableBool
{
    public function run($value): ?bool
    {
        if ($value) {
            return null;
        }

        return false;
    }
}

?>
