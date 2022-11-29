<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

class NonFinalNonTypedPrivateProperty
{
    /**
     * @var object
     */
    private $x;

    public function __construct(object $x)
    {
        $this->x = $x;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

class NonFinalNonTypedPrivateProperty
{
    public function __construct(private object $x)
    {
    }
}

?>
