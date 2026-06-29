<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleAssignRector\Fixture;

final class SkipCallInAssignTarget
{
    public function run($container)
    {
        $container->get(SomeService::class)->enabled = true;
        $container->get(SomeService::class)->enabled = true;
        $container->get(SomeService::class)->enabled = true;
    }
}
