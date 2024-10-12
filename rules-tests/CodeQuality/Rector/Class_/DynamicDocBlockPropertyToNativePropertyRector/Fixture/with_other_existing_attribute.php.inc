<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @property SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
#[\OtherAttribute]
final class WithOtherExistingAttribute
{
    public function run(): void
    {
        $this->someDependency = new SomeDependency();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

#[\OtherAttribute]
final class WithOtherExistingAttribute
{
    private ?\Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency $someDependency;
    public function run(): void
    {
        $this->someDependency = new SomeDependency();
    }
}

?>
