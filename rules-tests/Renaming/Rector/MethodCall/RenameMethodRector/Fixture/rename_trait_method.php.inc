<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

trait RenameTraitMethod
{
    private function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

trait RenameTraitMethod
{
    private function execute()
    {
    }
}

?>
