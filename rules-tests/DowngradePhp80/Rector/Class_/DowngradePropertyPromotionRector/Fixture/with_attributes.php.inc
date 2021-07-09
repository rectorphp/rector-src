<?php

namespace Rector\Tests\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector\Fixture;

class WithAttributes
{
    public function __construct(
        #[\Deprecated]
        private int $property = 0,
        // normal comment on property promotion should be moved to assign
        private int $property2 = 1,
        // normal comment on property promotion with Attribute will be kept and moved to assign as well
        #[\Deprecated]
        private int $property3 = 2,
        // normal comment on parameter should be kept
        int $normalParameter
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
    private int $property3 = 2;
    public function __construct(
        #[\Deprecated]
        int $property = 0,
        int $property2 = 1,
        // normal comment on property promotion with Attribute will be kept and moved to assign as well
        #[\Deprecated]
        int $property3 = 2,
        // normal comment on parameter should be kept
        int $normalParameter
    )
    {
        $this->property = $property;
        // normal comment on property promotion should be moved to assign
        $this->property2 = $property2;
        // normal comment on property promotion with Attribute will be kept and moved to assign as well
        $this->property3 = $property3;
    }
}
?>
