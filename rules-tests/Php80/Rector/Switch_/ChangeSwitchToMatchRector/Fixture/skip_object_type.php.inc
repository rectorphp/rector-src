<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

use Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Source\SomeResponse;

class SkipObjectType {
    public function doTheAction(SomeResponse $example): string
    {
        switch ($example) {
            case $example::build('a', 'b'):
                return 'here';
                break;
            default:
                return 'there';
        }
    }
}