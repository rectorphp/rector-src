<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class NarrowerPropertyType
{
    private object $entity;

    public function __construct(?object $entity)
    {
        $this->entity = $entity;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class NarrowerPropertyType
{
    public function __construct(private object $entity)
    {
    }
}

?>
