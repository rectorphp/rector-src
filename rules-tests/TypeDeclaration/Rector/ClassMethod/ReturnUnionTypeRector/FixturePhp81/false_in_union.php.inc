<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixturePhp81;

final class FalseInUnion
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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixturePhp81;

final class FalseInUnion
{
    public function run($value): false|string
    {
        if ($value) {
            return false;
        }

        return substr('warning', 1);
    }
}

?>
