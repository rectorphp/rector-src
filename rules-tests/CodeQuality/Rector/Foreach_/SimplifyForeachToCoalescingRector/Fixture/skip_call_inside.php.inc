<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

final class SkipCallInside
{
    private function getHeader(array $headers, $key)
    {
        $lowercaseHeader = strtolower($key);
        foreach ($headers as $k => $v) {
            if (strtolower($k) === $lowercaseHeader) {
                return $v;
            }
        }

        return null;
    }
}
