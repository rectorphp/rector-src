<?php

namespace Rector\Tests\DowngradePhp81\Rector\Property\DowngradeReadonlyClassRector\Fixture;

final readonly class ReadonlyClassWithPropertyPromotion
{
    public function __construct(private string $foo)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp81\Rector\Property\DowngradeReadonlyClassRector\Fixture;

final class ReadonlyClassWithPropertyPromotion
{
    public function __construct(private readonly string $foo)
    {
    }
}

?>
