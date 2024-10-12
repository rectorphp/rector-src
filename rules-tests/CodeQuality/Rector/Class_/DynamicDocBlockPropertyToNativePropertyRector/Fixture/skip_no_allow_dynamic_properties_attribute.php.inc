<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @property SomeDependency $someDependency
 */
final class SkipNoAllowDynamicPropertiesAttribute
{
    public function run(): void
    {
        $this->someDependency = new SomeDependency();
    }
}
