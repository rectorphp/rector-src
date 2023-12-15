<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class IntSingle
{
    public function run()
    {
        return -10;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class IntSingle
{
    public function run(): int
    {
        return -10;
    }
}

?>
