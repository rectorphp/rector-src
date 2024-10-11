<?php

namespace Rector\Tests\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector\Fixture;

class SkipNestedWithOrRootIf
{
    public function run()
    {
        $executed = false;
        foreach ($arrayOne as $key => $value) {
            if ($key < 7000 || $key == 10000) {
                if (array_key_exists($key, $arrayTwo)) {
                    $executed = true;
                }
            }
        }
        return $executed;
    }
}
