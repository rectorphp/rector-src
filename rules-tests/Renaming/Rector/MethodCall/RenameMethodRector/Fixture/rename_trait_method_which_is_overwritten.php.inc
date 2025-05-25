<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\SomeTrait;

final class RenameTraitMethodWhichIsOverwritten
{
    use SomeTrait;

    public function _test()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\SomeTrait;

final class RenameTraitMethodWhichIsOverwritten
{
    use SomeTrait;

    public function test()
    {
    }
}

?>
