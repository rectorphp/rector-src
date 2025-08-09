<?php

namespace Rector\Tests\Issues\WithPhpSets\Fixture;

final class Fixture
{
    public function run()
    {
        socket_set_timeout();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\WithPhpSets\Fixture;

final class Fixture
{
    public function run()
    {
        stream_set_timeout();
    }
}

?>
