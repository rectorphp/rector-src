<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class IfElseIfElsePartialNoReturn
{
    public function run(int $number)
    {
        if (rand(0, 1)) {
            return 1;
         } elseif (rand(0, 1)) {
            // no return here
         } else {
             return 2;
         }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class IfElseIfElsePartialNoReturn
{
    public function run(int $number)
    {
        if (rand(0, 1)) {
            return 1;
         } elseif (rand(0, 1)) {
            // no return here
         } else {
             return 2;
         }
        return null;
    }
}

?>
