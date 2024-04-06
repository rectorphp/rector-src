<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class RemoveAnotherClassMethodCallExtraArgument
{
    public function run()
    {
        $this->performBetter(1, 2);
    }

    function performBetter($value)
    {
        $arguments = better_func_get_args();
        var_dump($arguments);
    }
}

function better_func_get_args() {
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class RemoveAnotherClassMethodCallExtraArgument
{
    public function run()
    {
        $this->performBetter(1);
    }

    function performBetter($value)
    {
        $arguments = better_func_get_args();
        var_dump($arguments);
    }
}

function better_func_get_args() {
}

?>
