<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

final class Foo
{
    public function run(): void
    {
        ?>
        <p>Let's insert stuff here.</p>
        <?php
        if (random_int(1, 10) === 5) {
            $a = true;
        }

        if (isset($a)) {
            doStuff();
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

final class Foo
{
    public function run(): void
    {
        $a = null;
        ?>
        <p>Let's insert stuff here.</p>
        <?php
        if (random_int(1, 10) === 5) {
            $a = true;
        }

        if (isset($a)) {
            doStuff();
        }
    }
}

?>
