<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

class PhpDoc
{
    public function run()
    {
        $callable = fn () =>
            /**
             * @return bool
             */
            fn() => true;
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

class PhpDoc
{
    public function run()
    {
        $callable = function () {
            return
                /**
                 * @return bool
                 */
                function () {
                    return true;
                };
        };
    }
}

?>
