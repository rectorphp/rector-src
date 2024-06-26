<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class ForeachWithMultidimensionalAssociativeArray
{
    /**
     * @param array<int, array{id: int, name: string}> $users
     */
    public function run(array $users)
    {
        $translations = [
            'name' => 'Name',
        ];

        foreach ($users as $user) {
            echo $user['id'];
            echo sprintf('%s: %s', $translations['name'], $user['name']);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class ForeachWithMultidimensionalAssociativeArray
{
    /**
     * @param array<int, array{id: int, name: string}> $users
     */
    public function run(array $users)
    {
        $translations = [
            'name' => 'Name',
        ];

        foreach ($users as ['id' => $id, 'name' => $name]) {
            echo $id;
            echo sprintf('%s: %s', $translations['name'], $name);
        }
    }
}

?>
