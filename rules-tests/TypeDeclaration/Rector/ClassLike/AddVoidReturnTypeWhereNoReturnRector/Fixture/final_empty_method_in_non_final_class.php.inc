<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class FinalEmptyMethodInNonFinalClass
{
    final public function run()
    {
    }

    final public function throwOnly()
    {
        throw new \RuntimeException();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class FinalEmptyMethodInNonFinalClass
{
    final public function run(): void
    {
    }

    final public function throwOnly(): void
    {
        throw new \RuntimeException();
    }
}

?>
