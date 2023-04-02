<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector\Fixture;

class SkipEmtyCases
{
    public function run()
    {
        switch (true) {
        }
    }
}
