<?php

namespace Rector\Tests\Php72\Rector\Assign\ListEachRector\Fixture;

// @see https://3v4l.org/CfUnf#v7.1.16
function KeyOnly()
{
    $values = [ 1 => 'value'];
    list($key) = each($values);

    echo $key;
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\Assign\ListEachRector\Fixture;

// @see https://3v4l.org/CfUnf#v7.1.16
function KeyOnly()
{
    $values = [ 1 => 'value'];
    $key = key($values);

    echo $key;
}

?>
