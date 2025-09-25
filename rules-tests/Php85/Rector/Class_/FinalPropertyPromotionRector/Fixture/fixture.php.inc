<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Fixture;

class FinalPropertyPromotion
{
    public function __construct(
        /** @final */
        public string $id
    ){}
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Fixture;

class FinalPropertyPromotion
{
    public function __construct(
        final public string $id
    ){}
}

?>
