<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\FixturePhp82;

final class StandaloneNullableFalse
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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\FixturePhp82;

final class StandaloneNullableFalse
{
    public function run($value): ?false
    {
        if ($value) {
            return null;
        }

        return false;
    }
}

?>
