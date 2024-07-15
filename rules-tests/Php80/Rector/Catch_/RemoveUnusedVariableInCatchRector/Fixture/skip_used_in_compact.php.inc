<?php

namespace Rector\Tests\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector\Fixture;

final class SkipUsedInCompact
{
    public function run()
    {
        try {
        } catch (\Throwable $usedLater) {
            $this->log(compact('usedLater'));
        }
    }
}
