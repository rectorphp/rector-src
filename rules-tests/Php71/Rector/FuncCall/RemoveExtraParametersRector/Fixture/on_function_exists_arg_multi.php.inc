<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class OnFunctionExistsArgMulti
{
    public static function streamSupports(string $function, $resource): bool
    {
        if (true) {
            return function_exists($function, 'abc');
        }

        return function_exists($function, 'def') && $function($resource);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class OnFunctionExistsArgMulti
{
    public static function streamSupports(string $function, $resource): bool
    {
        if (true) {
            return function_exists($function);
        }

        return function_exists($function) && $function($resource);
    }
}

?>