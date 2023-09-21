<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\CustomType;

final class Caller
{
    public static function execute(): void
    {
        $demo = new CustomType();
        $demo->notify();
    }
}
?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\CustomType;

final class Caller
{
    public static function execute(): void
    {
        $demo = new CustomType();
        $demo->__invoke();
    }
}
?>
