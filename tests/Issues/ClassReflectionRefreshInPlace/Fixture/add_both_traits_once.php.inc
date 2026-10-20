<?php

namespace Rector\Tests\Issues\ClassReflectionRefreshInPlace\Fixture;

final class AddBothTraitsOnce
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassReflectionRefreshInPlace\Fixture;

final class AddBothTraitsOnce
{
    use \Rector\Tests\Issues\ClassReflectionRefreshInPlace\Source\OtherTrait;
    use \Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeTrait;
    public function run()
    {
    }
}

?>
