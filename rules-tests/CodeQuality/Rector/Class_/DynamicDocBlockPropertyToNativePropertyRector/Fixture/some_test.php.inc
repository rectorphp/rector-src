<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @property SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
final class SomeTest extends TestCase
{
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

final class SomeTest extends TestCase
{
    private \Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency $someDependency;
    protected function setUp(): void
    {
        parent::setUp();
        $this->someDependency = new SomeDependency();
    }
}

?>
