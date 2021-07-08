<?php

namespace Rector\Tests\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector\Fixture;

class WithAttributes
{
    public function __construct(
        #[\Deprecated]
        private int $property = 0
    ) {
    }
}
?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector\Fixture;

class WithAttributes
{
    private int $property = 0;
    public function __construct(
        #[\Deprecated]
        int $property = 0
    )
    {
        $this->property = $property;
    }
}
?>
