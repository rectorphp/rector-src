<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MultipleCases
{
    public function run(string $value)
    {
        switch ($value) {
            case 'v1':
            case 'v2':
            case 'v3':
                return 100;
            default:
                return 1000;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MultipleCases
{
    public function run(string $value)
    {
        return match ($value) {
            'v1', 'v2', 'v3' => 100,
            default => 1000,
        };
    }
}

?>
