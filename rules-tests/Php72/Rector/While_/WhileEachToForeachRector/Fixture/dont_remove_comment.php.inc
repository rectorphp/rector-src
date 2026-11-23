<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

function dontremovecomment()
{
    reset($callbacks);

    // a comment before
    while (list($key, $callback) = each($callbacks)) {
        // comment inside
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

function dontremovecomment()
{
    reset($callbacks);

    // a comment before
    foreach ($callbacks as $key => $callback) {
        // comment inside
    }
}

?>
