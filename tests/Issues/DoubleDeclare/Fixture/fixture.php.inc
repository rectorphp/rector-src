<?php

namespace Rector\Tests\Issues\DoubleDeclare\Fixture;

final class Fixture
{
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\DoubleDeclare\Fixture;

final class Fixture
{
}

?>
