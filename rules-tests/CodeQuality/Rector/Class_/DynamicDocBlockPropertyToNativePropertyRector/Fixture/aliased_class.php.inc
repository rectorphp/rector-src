<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source as SomeSource;

/**
 * @property SomeSource\SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
final class AliasedClass
{
    public function run(): void
    {
        $this->someDependency = new SomeSource\SomeDependency();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source as SomeSource;

final class AliasedClass
{
    private ?\Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency $someDependency;
    public function run(): void
    {
        $this->someDependency = new SomeSource\SomeDependency();
    }
}

?>
