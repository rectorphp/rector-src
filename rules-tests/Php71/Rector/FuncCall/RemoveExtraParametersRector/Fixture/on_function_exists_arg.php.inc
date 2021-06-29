<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class OnFunctionExistsArg
{
    public static function streamSupports(string $function, $resource): bool
    {
        if (true) {
            return function_exists($function);
        }

        return function_exists($function) && @$function($resource);
    }
}

?>
