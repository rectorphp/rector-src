<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

use Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeInterface;

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

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

use Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeInterface;

final readonly class RefreshOnClassChangeWithImportedInterface implements SomeInterface
{
    public function run(): SomeInterface
    {
        return $this;
    }
}

?>
