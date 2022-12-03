<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\Fixture;

final class VariableAutobinding
{
    public function filter(array $input, $defaultValue)
    {
        $output = [];
        foreach ($input as $key => $value) {
            if ($value !== $defaultValue) {
                $output[$key] = $value;
            }
        }

        return $output;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\Fixture;

final class VariableAutobinding
{
    public function filter(array $input, $defaultValue)
    {
        $output = [];
        $output = array_filter($input, fn($value) => $value !== $defaultValue);

        return $output;
    }
}

?>
