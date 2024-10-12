<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @property SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
final class PromotedPropertyPriority
{
    public function __construct(
        protected SomeDependency $someDependency
    ) {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

final class PromotedPropertyPriority
{
    public function __construct(
        protected SomeDependency $someDependency
    ) {
    }
}

?>
