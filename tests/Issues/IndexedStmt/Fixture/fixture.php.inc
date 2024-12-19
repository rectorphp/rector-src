<?php

namespace Rector\Tests\Issues\IndexedStmt\Fixture;

final class Fixture
{
    public function run()
    {
        'with index 0';
        'with index 1';
        'with index 2';
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\IndexedStmt\Fixture;

final class Fixture
{
    public function run()
    {
        'with index 0';
        'final index';
    }
}

?>
