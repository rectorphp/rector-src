<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @see SomeDependency
 */
#[\AllowDynamicProperties]
final class SkipNoPropertyDoc
{
    public function run(): void
    {
        $this->someDependency = new SomeDependency();
    }
}
