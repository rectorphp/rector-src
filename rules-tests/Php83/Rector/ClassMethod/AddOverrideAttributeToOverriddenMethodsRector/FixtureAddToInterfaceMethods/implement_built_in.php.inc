<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Countable;

final class ImplementBuiltIn implements Countable
{
    public function count(): int
    {
        return 0;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Countable;

final class ImplementBuiltIn implements Countable
{
    #[\Override]
    public function count(): int
    {
        return 0;
    }
}

?>
