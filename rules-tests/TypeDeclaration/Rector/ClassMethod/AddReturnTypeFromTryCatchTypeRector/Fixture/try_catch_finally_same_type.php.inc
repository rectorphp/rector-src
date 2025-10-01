<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class TryCatchFinallySameType
{
    public function run()
    {
        try {
            return 1;
        } catch (\RuntimeException $e) {
            return 2;
        } finally {
            return 3;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class TryCatchFinallySameType
{
    public function run(): int
    {
        try {
            return 1;
        } catch (\RuntimeException $e) {
            return 2;
        } finally {
            return 3;
        }
    }
}

?>
