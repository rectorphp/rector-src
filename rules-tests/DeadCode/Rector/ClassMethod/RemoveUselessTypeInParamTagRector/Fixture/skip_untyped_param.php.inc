<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

final class SkipUntypedParam
{
    /**
     * @param $n
     */
    public function run(string $n)
    {
    }
}
?>
