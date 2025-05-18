<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipWhenSwitchDoesNotAssignValue
{
    public function run()
    {
        switch ($input) {
            case "a":
                $this->a();
                break;
            case "b":
                $this->b();
                break;
        }
    }
    public function a() {}
    public function b() {}
}
?>
