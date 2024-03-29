<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

function assignArrayToString()
{
    $string = '';
    $string[] = 1;

    $string2 = '';
    $someRubishInBetween = 1;
    $string2[] = 1;

    $string3 = $string;
    $string = '';
    $string3 = $string;
    $string3[] = 1;
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

function assignArrayToString()
{
    $string = [];
    $string[] = 1;

    $string2 = [];
    $someRubishInBetween = 1;
    $string2[] = 1;

    $string3 = $string;
    $string = [];
    $string3 = $string;
    $string3[] = 1;
}

?>
