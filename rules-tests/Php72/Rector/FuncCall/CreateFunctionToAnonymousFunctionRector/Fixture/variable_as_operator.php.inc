<?php

namespace Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\Fixture;

class VariableAsOperator
{
    public function run()
    {
        $f = '>';
        $func = create_function('$a, $b', "return \$a $f \$b;");
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\Fixture;

class VariableAsOperator
{
    public function run()
    {
        $f = '>';
        $func = function ($a, $b) {
            return $a > $b;
        };
    }
}

?>
