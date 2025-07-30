<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SimpleTryCatch
{
    public function run()
    {
        try {
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SimpleTryCatch
{
    public function run(): int
    {
        try {
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}

?>
