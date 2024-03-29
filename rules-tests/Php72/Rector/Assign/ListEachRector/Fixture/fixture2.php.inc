<?php

namespace Rector\Tests\Php72\Rector\Assign\ListEachRector\Fixture;

function each2()
{
    list($key, $val) = each($opt->option);

    list ($tid,) = each($option->option);

    list(, $curr) = each($tree);
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\Assign\ListEachRector\Fixture;

function each2()
{
    $key = key($opt->option);
    $val = current($opt->option);
    next($opt->option);
    $tid = key($option->option);
    $curr = current($tree);
    next($tree);
}

?>
