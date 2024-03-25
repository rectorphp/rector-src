<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CasesMixUpDefault
{
    public function run(string $value)
    {
        switch ($value) {
            case 'foo':
                $string = 'foo';
                break;
            case 'other':
            default:
                $string = 'bar';
                break;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CasesMixUpDefault
{
    public function run(string $value)
    {
        $string = match ($value) {
            'foo' => 'foo',
            default => 'bar',
        };
    }
}

?>
