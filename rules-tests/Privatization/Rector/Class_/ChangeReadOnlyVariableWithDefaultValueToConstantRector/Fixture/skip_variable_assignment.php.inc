<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class SkipVariableAssignment
{
    /**
     * @var string[]
     */
    private const VALUES = ['a', 'b', 'c', 'd', 'e', 'a', 'b', 'c', 'd', 'e'];

    public function run()
    {
        $arr = [];
        foreach (self::VALUES as $key => $value) {
            $arr[$value] ??= 0;
            $arr[$value] += $key;
        }
        return $arr;
    }
}
