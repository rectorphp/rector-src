<?php

namespace Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\Fixture;

class QuotedVariableArg
{
    public function pk_gen()
    {
        $argL = func_get_args();
        $strFunc = array_shift($argL);
        $strArgs = join(', ',$argL);

        return create_function('$v', 'return '.$strFunc.'($v'.$strArgs.');');
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\Fixture;

class QuotedVariableArg
{
    public function pk_gen()
    {
        $argL = func_get_args();
        $strFunc = array_shift($argL);
        $strArgs = join(', ',$argL);

        return function ($v) use ($strFunc, $strArgs) {
            return $strFunc($strArgs);
        };
    }
}

?>
