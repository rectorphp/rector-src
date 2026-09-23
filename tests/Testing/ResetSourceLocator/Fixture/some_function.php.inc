<?php

namespace Rector\Tests\Testing\ResetSourceLocator\Fixture;

function some_function()
{
}

?>
-----
<?php

namespace Rector\Tests\Testing\ResetSourceLocator\Fixture;

function some_function(): void
{
}

?>
