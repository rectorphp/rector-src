<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class IfElseReturnInTryCatchFinallyNoReturn
{
    public function run(int $number)
    {
        if (rand(0, 1)) {
            try {
                return execute();
            } catch (\Exception $e) {
                return 2;
            } finally {
                echo 'here no return';
            }
         } else {
             return 2;
         }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class IfElseReturnInTryCatchFinallyNoReturn
{
    public function run(int $number)
    {
        if (rand(0, 1)) {
            try {
                return execute();
            } catch (\Exception $e) {
                return 2;
            } finally {
                echo 'here no return';
            }
         } else {
             return 2;
         }
        return null;
    }
}

?>