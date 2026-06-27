<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\Fixture;

class StringDefaultInIfElse
{
    public function run(array $keys, array $values): array
    {
        foreach ($keys as $key) {
            if (isset($values[$key])) {
                $row[$key] = $values[$key];
            } else {
                $row[$key] = '';
            }
        }

        return $row ?? [];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\InitVariableDefaultBeforeNullCoalesceReturnRector\Fixture;

class StringDefaultInIfElse
{
    public function run(array $keys, array $values): array
    {
        $row = [];
        foreach ($keys as $key) {
            if (isset($values[$key])) {
                $row[$key] = $values[$key];
            } else {
                $row[$key] = '';
            }
        }

        return $row;
    }
}

?>
