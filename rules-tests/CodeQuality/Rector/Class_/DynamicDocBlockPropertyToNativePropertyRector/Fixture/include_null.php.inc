<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

/**
 * @property SomeDependency $someDependency
 */
#[\AllowDynamicProperties]
final class IncludeNull
{
    protected $someDependency = null;
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency;

final class IncludeNull
{
    protected ?SomeDependency $someDependency = null;
}

?>
