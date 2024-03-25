<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CasesMixUpDefaultInFirstCase
{
    public function run(string $value)
    {
        switch ($value) {
            case 'foo':
            default:
                $string = 'foo';
                break;
            case 'other':
                $string = 'bar';
                break;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CasesMixUpDefaultInFirstCase
{
    public function run(string $value)
    {
        $string = match ($value) {
            'other' => 'bar',
            default => 'foo',
        };
    }
}

?>
