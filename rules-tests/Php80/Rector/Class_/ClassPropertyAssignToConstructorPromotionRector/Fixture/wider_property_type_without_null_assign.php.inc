<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class WiderPropertyTypeWithoutNullAssign
{
    private(set) ?object $entity;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }

}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class WiderPropertyTypeWithoutNullAssign
{
    public function __construct(private(set) ?object $entity)
    {
    }

}

?>
