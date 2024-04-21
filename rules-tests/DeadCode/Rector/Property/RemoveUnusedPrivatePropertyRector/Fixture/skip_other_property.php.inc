<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipPrivateOtherProperty
{
    private $myProp;

    public function pubMethod() {
        echo $this->getX()->myProp;
    }

    public function getX(): self {
        return new self();
    }
}
