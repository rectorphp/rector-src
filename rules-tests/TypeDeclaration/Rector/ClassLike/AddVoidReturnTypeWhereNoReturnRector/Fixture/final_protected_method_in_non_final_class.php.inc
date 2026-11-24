<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class FinalProtectedMethodInNonFinalClass
{
    final protected function run()
    {
        echo 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class FinalProtectedMethodInNonFinalClass
{
    final protected function run(): void
    {
        echo 1;
    }
}

?>
