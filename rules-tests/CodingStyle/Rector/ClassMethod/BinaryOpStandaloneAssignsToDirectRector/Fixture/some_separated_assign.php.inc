<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SomeSeparatedAssign
{
    public function run()
    {
        $first = 100;
        $second = 200;

        return $first <=> $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SomeSeparatedAssign
{
    public function run()
    {
        return 100 <=> 200;
    }
}

?>
