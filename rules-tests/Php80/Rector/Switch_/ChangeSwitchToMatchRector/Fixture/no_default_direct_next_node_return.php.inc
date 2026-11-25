<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class NoDefaultDirectNextNodeReturn
{
    public function run(string $value)
    {
        switch($value) {
            case 'A':
            case 'B':
                return 'AB';
            case 'C':
                return 'D';
        }

        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class NoDefaultDirectNextNodeReturn
{
    public function run(string $value)
    {
        return match ($value) {
            'A', 'B' => 'AB',
            'C' => 'D',
            default => $value,
        };
    }
}

?>
