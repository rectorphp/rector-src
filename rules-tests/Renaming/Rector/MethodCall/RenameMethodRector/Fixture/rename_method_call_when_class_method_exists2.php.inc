<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Foo2;

class RenameMethodCall2
{
    private function call()
    {
        $foo = new Foo2();
        $foo->old();
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Foo2;

class RenameMethodCall2
{
    private function call()
    {
        $foo = new Foo2();
        $foo->new();
    }
}

?>
