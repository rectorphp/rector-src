<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipNullSafePropertyFetch
{
    private $myProp;

    public function pubMethod() {
        echo $this->getX()?->myProp;
    }

    public function getX(): SkipNullSafePropertyFetch|null {
        return rand(0,1) ? new SkipNullSafePropertyFetch() : null;
    }
}
