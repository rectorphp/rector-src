<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use DateTimeInterface;

final class MergeWithNullableType
{
    private ?DateTimeInterface $time;

    public function __construct(DateTimeInterface $time = null)
    {
        $this->time = $time;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use DateTimeInterface;

final class MergeWithNullableType
{
    public function __construct(private ?DateTimeInterface $time = null)
    {
    }
}

?>
