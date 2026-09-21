<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class RemoveFinalModifier
{
    final protected function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class RemoveFinalModifier
{
    private function run()
    {
    }
}

?>
