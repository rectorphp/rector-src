<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class RectorRun
{
    /** @var positive-int */
    public int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class RectorRun
{
    public function __construct(
        /** @var positive-int */
        public int $count
    )
    {
    }
}

?>
