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
        if ($a === 1) {
        }
        echo 'this is new stmt after if';
        echo 'existing next stmt after if';
    }
}

?>
