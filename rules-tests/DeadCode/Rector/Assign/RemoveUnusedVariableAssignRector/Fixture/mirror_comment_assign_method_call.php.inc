<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class MirrorCommentAssignMethodCall
{
    public function run($db)
    {
        // execute query
        $value = $db->query();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class MirrorCommentAssignMethodCall
{
    public function run($db)
    {
        // execute query
        $db->query();
    }
}

?>
