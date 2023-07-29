<?php

namespace Rector\Tests\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector\Fixture;

final class SkipEmptyCases
{
    public function run()
    {
        switch(true) {
        }
    }
}
