<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\UndefinedStmtIndex\Fixture;

final class UndefinedStmtsIndex
{
    public function fetchIdentities(array $data, array $identities)
    {
        $mappedUsers = [];
        foreach ($identities as $id) {
            $array = $mappedUsers[$id->user_id]->identities;
            $array[] = $id;
            $mappedUsers[$id->user_id]->identities = $array;
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\UndefinedStmtIndex\Fixture;

final class UndefinedStmtsIndex
{
    public function fetchIdentities(array $data, array $identities)
    {
        $mappedUsers = [];
        foreach ($identities as $id) {
            $mappedUsers[$id->user_id]->identities[] = $id;
        }
    }
}

?>
