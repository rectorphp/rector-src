<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @property SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
final class ExistingProperty extends TestCase
{
    private SomeDependency $someDependency;

    protected function setUp(): void
    {
        parent::setUp();
        $this->someDependency = new SomeDependency();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

final class ExistingProperty extends TestCase
{
    private SomeDependency $someDependency;
    protected function setUp(): void
    {
        parent::setUp();
        $this->someDependency = new SomeDependency();
    }
}

?>
