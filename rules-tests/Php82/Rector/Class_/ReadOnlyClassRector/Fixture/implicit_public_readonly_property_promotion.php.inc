<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final class ImplicitPublicReadonlyPropertyPromotion
{
    public function __construct(readonly string $name)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final readonly class ImplicitPublicReadonlyPropertyPromotion
{
    public function __construct(public string $name)
    {
    }
}

?>
