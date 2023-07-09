<?php

namespace Rector\Tests\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector\Fixture;

final class SkipUsedLater
{
    public function run()
    {
        try {
        } catch (Throwable $usedLater) {
        }

        $this->process($usedLater);
    }
}
