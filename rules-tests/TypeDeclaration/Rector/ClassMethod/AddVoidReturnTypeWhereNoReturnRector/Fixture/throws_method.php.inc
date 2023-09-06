<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class ThrowsMethod
{
    protected function getValues()
    {
        if (rand(0,1)) {
            throw new \Exception();
        }

        echo 'hello world';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class ThrowsMethod
{
    protected function getValues(): void
    {
        if (rand(0,1)) {
            throw new \Exception();
        }

        echo 'hello world';
    }
}

?>
