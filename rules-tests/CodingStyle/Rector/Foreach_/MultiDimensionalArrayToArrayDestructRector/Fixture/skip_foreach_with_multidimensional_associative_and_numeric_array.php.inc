<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class SkipForeachWithMultidimensionalAssociativeAndNumericArray
{
    /**
     * @param array<int, array{0: int, id: int, name: string}> $users
     */
    public function run4(array $users)
    {
        foreach ($users as $user) {
            echo $user[0];
            echo $user['id'];
            echo sprintf('Name: %s', $user['name']);
        }
    }
}
