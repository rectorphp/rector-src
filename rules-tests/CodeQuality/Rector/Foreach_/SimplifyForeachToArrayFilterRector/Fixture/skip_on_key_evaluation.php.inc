<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\Fixture;

final class SkipOnKeyEvaluation
{
    public function filter1(array $input)
    {
        $output = [];
        foreach ($input as $key => $value) {
            if ($key) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    public function filter2(array $input)
    {
        $output = [];
        foreach ($input as $key => $value) {
            if (\is_string($key)) {
                $output[$key] = $value;
            }
        }

        return $output;
    }
}
