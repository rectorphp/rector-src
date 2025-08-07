<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class WithAttributeOnProperty
{
    #[MyAttr]
    public readonly string $id;
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class WithAttributeOnProperty
{
    #[MyAttr]
    public string $id;
}

?>
