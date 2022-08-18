<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayTest\Fixture;

function emptyArray()
{
    return array();
}

function singleElementArray()
{
    return array(1);
}

function manyElementArray()
{
    return array(1, 'abc', true);
}

?>
-----
<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayTest\Fixture;

function emptyArray()
{
    return [];
}

function singleElementArray()
{
    return [1];
}

function manyElementArray()
{
    return [1, 'abc', true];
}

?>
