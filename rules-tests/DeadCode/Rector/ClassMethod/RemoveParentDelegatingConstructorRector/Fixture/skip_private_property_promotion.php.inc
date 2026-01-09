<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Source\SomeParentWithPrivatePropertyPromotion;

class SkipPrivatePropertyPromotion extends SomeParentWithPrivatePropertyPromotion
{
    public function __construct(private \DateTime $d)
    {
        parent::__construct($d);
    }

    public function run()
    {
        return $this->d->format('Y-m-d');
    }
}
