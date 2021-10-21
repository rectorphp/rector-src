<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

use Iterator;

class NoUse
{
    public function run($foo, $bar, $baz)
    {
        $callable = fn () =>
            function (Iterator $iterator) {
                $baz = 'baz';

                foreach ($iterator as $foo => $bar) {
                    yield $foo => $bar;
                }
            };
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

use Iterator;

class NoUse
{
    public function run($foo, $bar, $baz)
    {
        $callable = function () {
            return function (Iterator $iterator) {
                $baz = 'baz';

                foreach ($iterator as $foo => $bar) {
                    yield $foo => $bar;
                }
            };
        };
    }
}

?>
