<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

final class NullableFalseIsAllowed
{
    public function run($value)
    {

        if (rand(0, 1)) {
            return false;
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

final class NullableFalseIsAllowed
{
    public function run($value): false|null
    {

        if (rand(0, 1)) {
            return false;
        }

        return null;
    }
}

?>
