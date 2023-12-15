<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class IntMultiple
{
    public function run()
    {
        if (false) {
            return 10;
        }

        return 0;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class IntMultiple
{
    public function run(): int
    {
        if (false) {
            return 10;
        }

        return 0;
    }
}

?>
