<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

final class FinalClassNonTypedProtectedProperty
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

final class FinalClassNonTypedProtectedProperty
{
    public function __construct(protected object $x)
    {
    }
}

?>
