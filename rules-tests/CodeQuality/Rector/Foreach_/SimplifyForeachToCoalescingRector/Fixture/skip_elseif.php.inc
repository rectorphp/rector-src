<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

class SkipElseIf
{
    public function transform(array $data): array
    {
        $namedData = [];
        foreach ($data as $key => $value) {
            if ($key === 0) {
                $namedData['default'] = $value;
            } elseif (!\is_int($key)) {
                $namedData[$key] = $value;
            }
        }

        return $namedData;
    }
}
