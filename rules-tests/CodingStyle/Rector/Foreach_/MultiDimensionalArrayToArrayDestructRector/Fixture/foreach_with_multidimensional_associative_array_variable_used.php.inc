<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class ForeachWithMultiDimensionalAssociativeArrayVariableUsed
{
    /**
     * @param array<int, array{id: int, name: string, email: list<string>}> $users
     */
    public function run(array $users)
    {
        foreach ($users as $user) {
            echo $user['id'];
            echo sprintf('Name: %s', $user['name']);

            foreach ($user['email'] as $email) {
                echo $email;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class ForeachWithMultiDimensionalAssociativeArrayVariableUsed
{
    /**
     * @param array<int, array{id: int, name: string, email: list<string>}> $users
     */
    public function run(array $users)
    {
        foreach ($users as ['id' => $id, 'name' => $name, 'email' => $email1]) {
            echo $id;
            echo sprintf('Name: %s', $name);

            foreach ($email1 as $email) {
                echo $email;
            }
        }
    }
}

?>
