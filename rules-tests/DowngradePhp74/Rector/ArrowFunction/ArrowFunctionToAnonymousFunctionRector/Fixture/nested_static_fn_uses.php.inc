<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

final class NestedStaticFnUses
{
    public function __invoke()
    {
        return fn ($foo) => fn ($bar) => fn () => [$foo, $bar];
    }
}
?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

final class NestedStaticFnUses
{
    public function __invoke()
    {
        return function ($foo) {
            return function ($bar) use ($foo) {
                return function () use ($foo, $bar) {
                    return [$foo, $bar];
                };
            };
        };
    }
}
?>
