<?php

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector\Source\EmptyMethod;

final class SkipInArg
{
    /**
     * @var EmptyMethod
     */
    private $emptyMethod;

    public function __construct(EmptyMethod $emptyMethod)
    {
        $this->emptyMethod = $emptyMethod;
    }

    public function run()
    {
        if ($this->process($this->emptyMethod->run())) {
            return true;
        }

        return false;
    }

    private function process()
    {
        return true;
    }
}
