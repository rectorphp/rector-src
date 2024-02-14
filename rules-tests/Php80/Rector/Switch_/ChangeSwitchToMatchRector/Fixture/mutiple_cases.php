<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MultipleCases
{
    public function run($value)
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
