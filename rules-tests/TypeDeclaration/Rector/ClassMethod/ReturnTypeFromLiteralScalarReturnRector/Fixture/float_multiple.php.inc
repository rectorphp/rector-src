<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class FloatMultiple
{
    public function run()
    {
        if (false) {
            return 10.0;
        }

        return 0.0;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class FloatMultiple
{
    public function run(): float
    {
        if (false) {
            return 10.0;
        }

        return 0.0;
    }
}

?>
