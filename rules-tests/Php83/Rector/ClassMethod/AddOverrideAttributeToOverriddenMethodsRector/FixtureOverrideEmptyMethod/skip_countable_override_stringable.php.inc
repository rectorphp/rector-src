<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Countable;
use Stringable;

final class SkipCountableOverrideStringable implements Countable, Stringable
{
    public function count(): int
    {
        return 0;
    }

    public function __toString()
    {
        return 'hello';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Countable;
use Stringable;

final class SkipCountableOverrideStringable implements Countable, Stringable
{
    public function count(): int
    {
        return 0;
    }

    #[\Override]
    public function __toString()
    {
        return 'hello';
    }
}

?>
