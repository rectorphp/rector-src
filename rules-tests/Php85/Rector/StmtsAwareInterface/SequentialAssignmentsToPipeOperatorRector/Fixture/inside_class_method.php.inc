<?php

namespace Rector\Tests\Php85\Rector\StmtsAwareInterface\SequentialAssignmentsToPipeOperatorRector\Fixture;

class InsideClassMethod
{
    public function run()
    {
        $value = "hello world";
        $result1 = function3($value);
        $result2 = function2($result1);
        $result = function1($result2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\StmtsAwareInterface\SequentialAssignmentsToPipeOperatorRector\Fixture;

class InsideClassMethod
{
    public function run()
    {
        $value = "hello world";
        $result = $value
            |> function3(...)
            |> function2(...)
            |> function1(...);
    }
}

?>
