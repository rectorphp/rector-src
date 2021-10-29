<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipUnionConsistNullType
{
    /** @var null|\stdClass|int */
    public $cb;

    public function __construct($cb = null)
    {
        $this->cb = $cb;
    }
}
