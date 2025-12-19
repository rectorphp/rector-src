<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class SkipDifferentTypePropertyAndParamType
{
    private ?\stdClass $customerInput;

    public function __construct(
        \stdClass $customerInput,
    ) {
        $this->customerInput = $customerInput;
    }

    public function markResolved(): void
    {
        $this->customerInput = null;
    }
}
