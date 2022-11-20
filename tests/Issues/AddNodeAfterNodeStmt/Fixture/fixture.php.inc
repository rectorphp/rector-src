<?php

namespace Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Fixture;

final class Fixture
{
    public function run()
    {
        if ($a === 1) {
        }
        echo 'existing next stmt';
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Fixture;

final class Fixture
{
    public function run()
    {
        if ($a === 1) {
        }
        echo 'this is new stmt after stmt';
        echo 'existing next stmt';
    }
}

?>
