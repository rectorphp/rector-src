<?php

namespace Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Fixture;

final class Fixture
{
    public function run()
    {
        if ($a === 1) {
        }
        echo 'existing next stmt after if';
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
        echo 'this is new stmt before if';
        if ($a === 1) {
        }
        echo 'existing next stmt after if';
    }
}

?>
