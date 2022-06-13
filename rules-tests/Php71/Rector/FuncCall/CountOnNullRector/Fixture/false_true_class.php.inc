<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

use stdClass;

function countables2()
{
    $values = false;
    $count = count($values);

    $values = true;
    $count = count($values);

    $values = new stdClass();
    $count = count($values);
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

use stdClass;

function countables2()
{
    $values = false;
    $count = is_array($values) || $values instanceof \Countable ? count($values) : (is_null($values) ? 0 : 1);

    $values = true;
    $count = is_array($values) || $values instanceof \Countable ? count($values) : (is_null($values) ? 0 : 1);

    $values = new stdClass();
    $count = is_array($values) || $values instanceof \Countable ? count($values) : (is_null($values) ? 0 : 1);
}

?>
