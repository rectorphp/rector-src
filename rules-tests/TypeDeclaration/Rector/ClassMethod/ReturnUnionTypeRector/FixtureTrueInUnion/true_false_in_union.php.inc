<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

final class TrueFalseInUnion
{
    public function run($value)
    {
        if ($value) {
            return true;
        }

        if (rand(0, 1)) {
            return false;
        }

        return substr('warning', 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

final class TrueFalseInUnion
{
    public function run($value): bool|string
    {
        if ($value) {
            return true;
        }

        if (rand(0, 1)) {
            return false;
        }

        return substr('warning', 1);
    }
}

?>
