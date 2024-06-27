<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipCallableType
{
    /** @var callable */
    private $fallback;

    /** @var callable|array */
    public $cb;

    /**
     * @param mixed[] $cache
     */
    public function __construct(
        callable $fallback,
        private array $cache = [],
        callable|array $cb = null
    )
    {
        $this->fallback = $fallback;
        $this->cb = $cb;
    }
}
