<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class ImplicitPublicReadonlyPropertyPromotion
{
    public function __construct(readonly string $name)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class ImplicitPublicReadonlyPropertyPromotion
{
    public function __construct(public string $name)
    {
    }
}

?>
