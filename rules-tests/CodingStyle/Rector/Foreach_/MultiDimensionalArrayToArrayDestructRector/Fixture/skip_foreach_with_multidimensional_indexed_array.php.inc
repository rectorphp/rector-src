<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class SkipForeachWithMultidimensionalIndexedArray
{
    /**
     * @param array<int, array{0: int, 1: string}> $users
     */
    public function run5(array $users)
    {
        foreach ($users as $user) {
            echo $user[0];
            echo sprintf('Name: %s', $user[0]);
        }
    }
}
