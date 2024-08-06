<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Source\Collection;

final class ExecuteOnCallsWithNonForeachVariable
{
    public function foreachToInArrayWithFunctionCalls(array $items): bool
    {
        $local = 'local';

        foreach ($items as $item) {
            if ($item == strtoupper(strtolower($local))) {
                return true;
            }
        }

        return false;
    }

    public function foreachToInArrayWithMethodCall(array $items): bool
    {
        $local = 'local';

        foreach ($items as $item) {
            if ($item == $this->strtoupper($local)) {
                return true;
            }
        }

        return false;
    }

    public function foreachToInArrayWithStaticCall(array $items): bool
    {
        $local = 'local';

        foreach ($items as $item) {
            if ($item == SkipMethodCalls::strtolower($local)) {
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

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Source\Collection;

final class ExecuteOnCallsWithNonForeachVariable
{
    public function foreachToInArrayWithFunctionCalls(array $items): bool
    {
        $local = 'local';
        return in_array(strtoupper(strtolower($local)), $items);
    }

    public function foreachToInArrayWithMethodCall(array $items): bool
    {
        $local = 'local';
        return in_array($this->strtoupper($local), $items);
    }

    public function foreachToInArrayWithStaticCall(array $items): bool
    {
        $local = 'local';
        return in_array(SkipMethodCalls::strtolower($local), $items);
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

?>
