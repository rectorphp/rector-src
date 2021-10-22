<?php

namespace Rector\Tests\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector\Fixture;

final class NestedStaticFnUses
{
    public function __invoke()
    {
        return fn ($foo) => fn ($bar) => fn () => [$foo, $bar];
    }

    public function many()
    {
        return fn ($foo) => fn ($bar) => fn ($baz) => fn () => [$foo, $bar, $baz];
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

    public function many()
    {
        return function ($foo) {
            return function ($bar) use ($foo) {
                return function ($baz) use ($foo, $bar) {
                    return function () use ($foo, $bar, $baz) {
                        return [$foo, $bar, $baz];
                    };
                };
            };
        };
    }
}
?>
