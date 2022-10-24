<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipCallableTypeDifferentDefinition2
{
    /** @var \stdClass */
    private $fallback;

    public function __construct(callable $fallback)
    {
        $this->fallback = $fallback;
    }
}

?>
