<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

final class FinalClassProtectedProperty
{
    /**
     * @var object
     */
    protected $x;

    public function __construct(object $x)
    {
        $this->x = $x;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

final class FinalClassProtectedProperty
{
    public function __construct(protected object $x)
    {
    }
}

?>
