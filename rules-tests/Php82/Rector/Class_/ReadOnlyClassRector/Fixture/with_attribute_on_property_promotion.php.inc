<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final class WithAttributeOnPropertyPromotion
{
    private function __construct(
        #[MyAttr]
        private readonly string $id
    ){}
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final readonly class WithAttributeOnPropertyPromotion
{
    private function __construct(
        #[MyAttr]
        private string $id
    ){}
}

?>
