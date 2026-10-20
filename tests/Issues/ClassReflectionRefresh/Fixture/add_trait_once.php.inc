<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

class AddTraitOnce
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

final class AddTraitOnce
{
    use \Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeTrait;
    public function run()
    {
    }
}

?>
