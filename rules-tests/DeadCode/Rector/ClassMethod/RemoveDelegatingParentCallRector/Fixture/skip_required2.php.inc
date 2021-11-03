<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector\Fixture;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SkipRequired extends ParentClassWithoutRequired
{
    #[Required]
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        parent::setEventDispatcher($eventDispatcher);
    }
}

class ParentClassWithoutRequired
{
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
    }
}
