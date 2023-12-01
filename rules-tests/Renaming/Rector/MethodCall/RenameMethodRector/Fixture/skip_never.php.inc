<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

class DemoFile
{
    /** @var never **/
    public $messageBus;

    public function run(): void
    {
        $this->messageBus->reset();
    }
}
