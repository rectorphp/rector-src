<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Source\Collection;

final class SkipOnCallsWithForeachVariable
{
    public function foreachToInArrayWithFunctionCalls($items): bool
    {
        foreach ($items as $item) {
            if ($item == strtoupper(strtolower($item))) {
                return true;
            }
        }

        return false;
    }

    public function foreachToInArrayWithMethodCall($items): bool
    {
        foreach ($items as $item) {
            if ($item == $this->strtoupper($item)) {
                return true;
            }
        }

        return false;
    }

    public function foreachToInArrayWithStaticCall($items): bool
    {
        foreach ($items as $item) {
            if ($item == SkipMethodCalls::strtolower($item)) {
                return true;
            }
        }

        return false;
    }

    public static function strtolower($item): string
    {
        return strtolower((string) $item);
    }

    private function strtoupper($item): string
    {
        return strtoupper((string) $item);
    }
}
