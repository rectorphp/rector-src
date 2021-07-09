<?php

namespace Rector\Tests\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector\Fixture;

class WithAttributes
{
    public function __construct(
        #[\Deprecated]
        private int $property = 0,
        // normal comment should be deleted
        private int $property2 = 1
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
    private int $property2 = 1;
    public function __construct(
        #[\Deprecated]
        int $property = 0,
        int $property2 = 1
    )
    {
        $this->property = $property;
        $this->property2 = $property2;
    }
}
?>
