<?php

namespace Rector\Tests\Issues\DynamicDocblockRename\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source as SomeSource;

/**
 * @property SomeSource\SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
final class Fixture
{
    public function run(): void
    {
        $this->someDependency = new SomeSource\SomeDependency();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\DynamicDocblockRename\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source as SomeSource;

final class Fixture
{
    private ?\stdClass $someDependency;
    public function run(): void
    {
        $this->someDependency = new \stdClass();
    }
}

?>
