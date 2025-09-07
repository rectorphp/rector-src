<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

class TypedParams
{
    function run1($optional = 1, int $required)
    {
    }

    function run2($optional = 1, string|int $required)
    {
    }

    function run3($optional = 1, null|int $required)
    {
    }

    function run4($optional = 1, A&B $required)
    {
    }

    function run5($optional = 1, array $required)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

class TypedParams
{
    function run1($optional = 1, int $required = 0)
    {
    }

    function run2($optional = 1, string|int $required = '')
    {
    }

    function run3($optional = 1, null|int $required = null)
    {
    }

    function run4($optional = 1, (A&B)|null $required = null)
    {
    }

    function run5($optional = 1, array $required = [])
    {
    }
}

?>
