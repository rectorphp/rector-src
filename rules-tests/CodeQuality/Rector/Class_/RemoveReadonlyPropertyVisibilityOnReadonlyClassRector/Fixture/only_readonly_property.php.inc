<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class OnlyReadonlyProperty
{
   private readonly string $property;
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class OnlyReadonlyProperty
{
   private string $property;
}

?>
