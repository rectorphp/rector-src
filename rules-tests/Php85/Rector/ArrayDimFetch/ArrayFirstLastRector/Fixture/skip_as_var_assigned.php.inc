<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipAsVarAssigned
{
    public function run()
    {
        $myArray = [
            'test'=> ['label'=> ['show'=> false]]
        ];

        $myArray[array_key_last($myArray)]['label']['show'] = true;
        var_dump($myArray);
    }
}
