<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Source;

trait TraitConsumer
{
    public function execute()
    {
        $this->run();
    }
}
