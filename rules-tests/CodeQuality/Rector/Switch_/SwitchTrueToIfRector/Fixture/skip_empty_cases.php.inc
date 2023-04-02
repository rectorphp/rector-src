<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector\Fixture;

class SkipEmptyCases
{
    public function run()
    {
        switch (true) {
        }
    }
}
