<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipCallableTypeDifferentDefinition
{
    /** @var CallbackHandler */
    private $fallback;

    /**
     * @param mixed[] $cache
     */
    public function __construct(callable $fallback)
    {
        $this->fallback = $fallback;
    }
}

?>
