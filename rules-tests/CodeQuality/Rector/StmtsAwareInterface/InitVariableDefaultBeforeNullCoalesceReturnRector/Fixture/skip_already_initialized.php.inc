<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\Fixture;

class SkipAlreadyInitialized
{
    public function run(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $key;
        }

        return $result ?? [];
    }
}
