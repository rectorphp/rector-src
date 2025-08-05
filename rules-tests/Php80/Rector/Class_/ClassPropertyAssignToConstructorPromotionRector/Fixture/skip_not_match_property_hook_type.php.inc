<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class SkipNotMatchPropertyHookType {
    protected \stdClass $origin {
        set(array|\stdClass $origin) {
            $this->origin = is_array($origin) ? (object) $origin : clone $origin;
        }
    }

    public function __construct(
        array|\stdClass $origin
    ) {
        $this->origin = $origin;
    }
}