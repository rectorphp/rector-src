<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\SubscriberInterface;

interface WhenInterfaceAndParentInterface extends SubscriberInterface
{
    public function old(): int;
}
