<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\Fixture;

final class PrivateMethod
{
    private function run()
    {
        $values = [];

        return $values;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\Fixture;

final class PrivateMethod
{
    private function run(): array
    {
        $values = [];

        return $values;
    }
}

?>
