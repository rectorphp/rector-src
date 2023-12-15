<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class StringHerenowdoc
{
    public function run()
    {
        return <<<TEST
test
TEST;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class StringHerenowdoc
{
    public function run(): string
    {
        return <<<TEST
test
TEST;
    }
}

?>
