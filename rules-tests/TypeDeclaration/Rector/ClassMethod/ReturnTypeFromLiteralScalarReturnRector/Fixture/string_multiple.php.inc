<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class StringMultiple
{
    public function run()
    {
        if (false) {
            return 'hello';
        }

        return 'world';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class StringMultiple
{
    public function run(): string
    {
        if (false) {
            return 'hello';
        }

        return 'world';
    }
}

?>
