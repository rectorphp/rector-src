<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\Fixture;

class ArrayDimFill
{
    public function run(array $keys): array
    {
        foreach ($keys as $key) {
            $result[$key] = $key;
        }

        return $result ?? [];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\Fixture;

class ArrayDimFill
{
    public function run(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $key;
        }

        return $result;
    }
}

?>
