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

    function run6($optional = 1, true $required)
    {
    }

    function run7($optional = 1, false $required)
    {
    }

    function run8($optional = 1, null $required)
    {
    }

    function run9($optional = 1, mixed $required)
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

    function run6($optional = 1, true $required = true)
    {
    }

    function run7($optional = 1, false $required = false)
    {
    }

    function run8($optional = 1, null $required = null)
    {
    }

    function run9($optional = 1, mixed $required = null)
    {
    }
}

?>
