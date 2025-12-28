<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class AsArg
{
    public function run()
    {
        $this->execute(function() {
            // some comment
            /** @psalm-suppress UndefinedFunction */
            return ff();
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class AsArg
{
    public function run()
    {
        $this->execute(
            // some comment
            /** @psalm-suppress UndefinedFunction */
            fn() => ff());
    }
}

?>
