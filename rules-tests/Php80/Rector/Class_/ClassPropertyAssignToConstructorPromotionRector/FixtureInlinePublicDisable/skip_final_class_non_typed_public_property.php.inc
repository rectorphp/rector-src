<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixtureInlinePublicDisable;

final class SkipFinalClassNonTypedPublicProperty
{
    public $name;

    public function __construct(string $name = '')
    {
        $this->name = $name;
    }
}
