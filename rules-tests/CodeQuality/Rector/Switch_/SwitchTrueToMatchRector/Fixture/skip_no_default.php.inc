<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector\Fixture;

class SkipNoDefault
{
    public function run($value)
    {
        switch (true) {
            case $value === 0:
                return 'no';
            case $value === 1:
                return 'yes';
        }
    }
}
