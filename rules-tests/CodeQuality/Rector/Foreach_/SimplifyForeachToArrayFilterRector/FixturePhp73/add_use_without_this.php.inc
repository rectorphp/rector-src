<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\Fixture;

final class AddUseWithoutThis
{
    private $defaultValue = '';

    public function filter(array $input)
    {
        $output = [];
        foreach ($input as $key => $value) {
            if ($value !== $this->defaultValue) {
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

final class AddUseWithoutThis
{
    private $defaultValue = '';

    public function filter(array $input)
    {
        $output = [];
        $output = array_filter($input, function ($value) {
            return $value !== $this->defaultValue;
        });

        return $output;
    }
}

?>
