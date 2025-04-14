<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture\Enum;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Enum\SomeEnumWithMethod;

final class RenameEnumMethod
{
    public function run()
    {
        $result = SomeEnumWithMethod::FIRST->oldEnumMethod();
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture\Enum;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Enum\SomeEnumWithMethod;

final class RenameEnumMethod
{
    public function run()
    {
        $result = SomeEnumWithMethod::FIRST->newEnumMethod();
    }
}

?>
