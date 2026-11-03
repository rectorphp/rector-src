<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipNestedConditionalAssign
{
    private string $a = '';

    public function __construct(bool $value)
    {
        if ($value) {
            if (mt_rand(0, 1)) {
                $this->a = 'a';
            } else {
                $this->a = 'b';
            }
        }

        echo $this->a;
    }
}
