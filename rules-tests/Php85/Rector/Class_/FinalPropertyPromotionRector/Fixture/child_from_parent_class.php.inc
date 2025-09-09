<?php

namespace Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Fixture;

use Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Source\ParentClass;

class ChildExtendsParent extends ParentClass
{
    public function __construct(
        /** @final */
        public string $idd
    ){}
}
?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Fixture;

use Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Source\ParentClass;

class ChildExtendsParent extends ParentClass
{
    public function __construct(
        final public string $idd
    ){}
}
?>
