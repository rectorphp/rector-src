<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\Fixture;

class SkipNoArrayDimWrite
{
    public function run(array $config): array
    {
        return $config['items'] ?? [];
    }
}
