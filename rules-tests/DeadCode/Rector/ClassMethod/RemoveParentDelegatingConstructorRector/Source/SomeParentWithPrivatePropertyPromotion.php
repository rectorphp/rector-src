<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Source;

class SomeParentWithPrivatePropertyPromotion
{
    public function __construct(private \DateTime $d)
    {
    }
}
