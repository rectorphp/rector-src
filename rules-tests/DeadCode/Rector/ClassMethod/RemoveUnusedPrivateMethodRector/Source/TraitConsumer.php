<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Source;

trait TraitConsumer
{
    public function execute()
    {
        $this->run();
    }

    public static function helloExecute()
    {
        self::hello();
    }
}
