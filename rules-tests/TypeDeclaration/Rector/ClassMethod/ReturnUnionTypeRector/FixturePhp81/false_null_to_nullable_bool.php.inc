<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixturePhp81;

final class FalseNullToNullableBool
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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixturePhp81;

final class FalseNullToNullableBool
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
