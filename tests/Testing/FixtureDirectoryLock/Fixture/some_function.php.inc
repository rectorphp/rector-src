<?php

namespace Rector\Tests\Testing\FixtureDirectoryLock\Fixture;

function some_function()
{
}

?>
-----
<?php

namespace Rector\Tests\Testing\FixtureDirectoryLock\Fixture;

function some_function(): void
{
}

?>
