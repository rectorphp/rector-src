<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class SkipSideEffectInitial
{
    public function run(bool $value): array
    {
        $result = $this->init();
        if ($value) {
            $result = [1, 2, 3];
        } else {
            $result = [4, 5, 6];
        }

        return $result;
    }

    private function init(): array
    {
        echo 'side effect';

        return [];
    }
}
