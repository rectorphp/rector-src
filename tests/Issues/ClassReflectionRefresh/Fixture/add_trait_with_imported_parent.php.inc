<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

use Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeParentClass;

final class AddTraitWithImportedParent extends SomeParentClass
{
    public function run(): SomeParentClass
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

use Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeParentClass;

final class AddTraitWithImportedParent extends SomeParentClass
{
    use \Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeTrait;
    public function run(): SomeParentClass
    {
        return $this;
    }
}

?>
