<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector\Fixture;

class WithMiddleVariable
{
    public function run()
    {
        $prefix = '';
        $main = '';
        $suffix = '';

        return "$prefix$main$suffix";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector\Fixture;

class WithMiddleVariable
{
    public function run()
    {
        $prefix = '';
        $main = '';
        $suffix = '';

        return "{$prefix}{$main}{$suffix}";
    }
}

?>
