<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class SkipForeachWithMultidimensionalAssociativeAndNumericArray
{
    /**
     * @param array<int, array{0: int, id: int, name: string}> $users
     */
    public function run(array $users)
    {
        foreach ($users as $user) {
            echo $user[0];
            echo $user['id'];
            echo sprintf('Name: %s', $user['name']);
        }
    }
}
