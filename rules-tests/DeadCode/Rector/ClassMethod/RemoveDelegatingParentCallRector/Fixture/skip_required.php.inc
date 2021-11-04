<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector\Source\ParentClassWithoutRequired;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SkipRequired extends ParentClassWithoutRequired
{
    /**
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        parent::setEventDispatcher($eventDispatcher);
    }
}

