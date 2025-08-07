<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class OnlyReadonlyProperty2
{
    public function __construct(private readonly string $property)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class OnlyReadonlyProperty2
{
    public function __construct(private string $property)
    {
    }
}

?>
