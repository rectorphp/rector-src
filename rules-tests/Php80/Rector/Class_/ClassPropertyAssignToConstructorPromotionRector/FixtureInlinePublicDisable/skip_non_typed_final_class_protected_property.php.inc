<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

class SkipNonTypedFinalClassProtectedProperty
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
