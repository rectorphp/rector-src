<?php

namespace Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\FinalPropertyPromotionRector\Source\FinalParent;

class ChildExtendsFinalParent extends FinalParent
{
    public function __construct(
        /** @final */
        public string $id
    ){}
}
?>
