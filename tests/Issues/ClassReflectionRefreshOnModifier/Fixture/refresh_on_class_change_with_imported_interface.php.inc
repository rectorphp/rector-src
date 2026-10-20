<?php

namespace Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Fixture;

use Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Source\SomeInterface;

class RefreshOnClassChangeWithImportedInterface implements SomeInterface
{
    public function run(): SomeInterface
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Fixture;

use Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Source\SomeInterface;

final readonly class RefreshOnClassChangeWithImportedInterface implements SomeInterface
{
    public function run(): SomeInterface
    {
        return $this;
    }
}

?>
