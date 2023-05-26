<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use PhpParser\Node\Expr\ClassConstFetch;

final class DoubleMethodCall
{
    public function run()
    {
        $magicGet = $this->createClassConstFetch('SomeClass', 'MAGIC_GET');
        echo $magicGet;

        $magicSet = $this->createClassConstFetch('SomeClass', 'MAGIC_SET');
        echo $magicSet;
    }

    protected function createClassConstFetch(string $class, string $constant): ClassConstFetch
    {
        return new ClassConstFetch($class, $constant);
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use PhpParser\Node\Expr\ClassConstFetch;

final class DoubleMethodCall
{
    public function run()
    {
        $classConstFetch = $this->createClassConstFetch('SomeClass', 'MAGIC_GET');
        echo $classConstFetch;

        $magicSet = $this->createClassConstFetch('SomeClass', 'MAGIC_SET');
        echo $magicSet;
    }

    protected function createClassConstFetch(string $class, string $constant): ClassConstFetch
    {
        return new ClassConstFetch($class, $constant);
    }
}

?>
