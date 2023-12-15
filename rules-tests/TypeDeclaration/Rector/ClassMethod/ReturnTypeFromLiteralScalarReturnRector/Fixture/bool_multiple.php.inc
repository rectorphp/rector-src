<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class BoolMultiple
{
    public function run()
    {
        if (false) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class BoolMultiple
{
    public function run(): bool
    {
        if (false) {
            return false;
        }

        return true;
    }
}

?>
