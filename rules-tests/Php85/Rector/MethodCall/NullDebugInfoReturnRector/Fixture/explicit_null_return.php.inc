<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

final class ExplicitNullReturn
{
    public function __debugInfo(): ?array
    {
        return null;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

final class ExplicitNullReturn
{
    public function __debugInfo(): ?array
    {
        return [];
    }
}

?>
