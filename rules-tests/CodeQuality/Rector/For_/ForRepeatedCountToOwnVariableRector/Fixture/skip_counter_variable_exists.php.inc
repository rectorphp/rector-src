<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class SkipCounterVariableExists
{
    public function run($items)
    {
        $counter = 0;
        for ($i = 5; $i <= count($items); $i++) {
            echo $items[$i];
        }
        echo $counter++;
    }
}