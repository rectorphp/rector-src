<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipPropertyFetchOverriddenByNextMethodCall
{
    private bool $executed;

    public function run(): void
    {
        $this->executed = false;

        $this->execute();

        if (!$this->executed) {
            echo 'not run';
        }
    }

    protected function execute()
    {
        if (rand(0, 1)) {
            $this->executed = true;
        }
    }
}
