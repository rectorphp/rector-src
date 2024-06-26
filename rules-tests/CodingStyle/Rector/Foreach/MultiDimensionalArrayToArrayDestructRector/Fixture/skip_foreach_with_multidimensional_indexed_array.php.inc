<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class SkipForeachWithMultidimensionalIndexedArray
{
    /**
     * @param array<int, array{0: int, 1: string}> $users
     */
    public function run(array $users)
    {
        foreach ($users as $user) {
            echo $user[0];
            echo sprintf('Name: %s', $user[0]);
        }
    }
}
