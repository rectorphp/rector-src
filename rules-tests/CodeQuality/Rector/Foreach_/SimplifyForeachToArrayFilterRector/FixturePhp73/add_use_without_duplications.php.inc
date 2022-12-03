<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\Fixture;

final class AddUseWithoutDuplications
{
    public function filter(array $input, $defaultValue)
    {
        $output = [];
        foreach ($input as $key => $value) {
            if ($value !== $defaultValue && $value !== $defaultValue + 1) {
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

final class AddUseWithoutDuplications
{
    public function filter(array $input, $defaultValue)
    {
        $output = [];
        $output = array_filter($input, function ($value) use ($defaultValue) {
            return $value !== $defaultValue && $value !== $defaultValue + 1;
        });

        return $output;
    }
}

?>
