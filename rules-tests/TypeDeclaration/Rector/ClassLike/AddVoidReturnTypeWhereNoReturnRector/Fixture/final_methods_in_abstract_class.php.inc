<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

abstract class FinalMethodsInAbstractClass
{
    final public function run()
    {
        echo 1;
    }

    final protected function prot()
    {
        echo 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

abstract class FinalMethodsInAbstractClass
{
    final public function run(): void
    {
        echo 1;
    }

    final protected function prot(): void
    {
        echo 1;
    }
}

?>
