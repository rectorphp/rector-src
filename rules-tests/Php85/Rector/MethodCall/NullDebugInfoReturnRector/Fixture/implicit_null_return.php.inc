<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

final class Fixture
{
    public function __debugInfo(): ?array
    {
        return;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

final class Fixture
{
    public function __debugInfo(): ?array
    {
        return [];
    }
}

?>
