<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipMultipleCatchWithUseSameVariable
{
    public function run()
    {
        try {
        } catch (\Exception $e) {
            var_dump($e);
        } catch (\Throwable $e) {
            var_dump($e);
        }
    }
}
