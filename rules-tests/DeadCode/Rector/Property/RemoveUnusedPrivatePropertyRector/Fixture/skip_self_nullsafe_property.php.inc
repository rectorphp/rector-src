<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipNullSafeSelfPropertyFetch
{
    private $myProp;

    public function pubMethod() {
        echo $this->getX()?->myProp;
    }

    public function getX(): self|null {
        return rand(0,1) ? new self() : null;
    }
}
