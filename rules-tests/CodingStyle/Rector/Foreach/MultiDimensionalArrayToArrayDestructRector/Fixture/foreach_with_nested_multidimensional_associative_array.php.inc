<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class ForeachWithNestedMultidimensionalAssociativeArray
{
    /**
     * @param array<int, array{id: int, name: string, emails: list<string>}> $users
     */
    public function run(array $users)
    {
        foreach ($users as $user) {
            echo $user['id'];
            echo sprintf('Name: %s', $user['name']);

            foreach ($user['emails'] as $email) {
                echo $email;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class ForeachWithNestedMultidimensionalAssociativeArray
{
    /**
     * @param array<int, array{id: int, name: string, emails: list<string>}> $users
     */
    public function run(array $users)
    {
        foreach ($users as ['id' => $id, 'name' => $name, 'emails' => $emails]) {
            echo $id;
            echo sprintf('Name: %s', $name);

            foreach ($emails as $email) {
                echo $email;
            }
        }
    }
}

?>
