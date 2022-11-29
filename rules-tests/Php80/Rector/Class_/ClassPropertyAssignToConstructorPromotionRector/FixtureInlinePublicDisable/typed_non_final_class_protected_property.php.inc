<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

class TypedNonFinalClassProtectedProperty
{
    protected object $x;

    public function __construct(object $x)
    {
        $this->x = $x;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

class TypedNonFinalClassProtectedProperty
{
    public function __construct(protected object $x)
    {
    }
}

?>