<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class WithAttributeOnPropertyPromotion
{
    private function __construct(
        #[MyAttr]
        private readonly string $id
    ){}
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class WithAttributeOnPropertyPromotion
{
    private function __construct(
        #[MyAttr]
        private string $id
    ){}
}

?>
