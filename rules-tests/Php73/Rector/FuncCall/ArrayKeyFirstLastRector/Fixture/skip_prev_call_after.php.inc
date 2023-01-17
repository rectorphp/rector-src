<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class SkipPrevCallAfter
{
    public function run()
    {
        $items = [1, 2, 3];

        end($items);
        $key = key($items);

        while ($key !== null) {
            $isFound = rand(0, 1) ? true : false;

            if (! $isFound) {
                prev($items);
                $key = key($items);

                continue;
            }

            return current($items);
        }
    }
}
