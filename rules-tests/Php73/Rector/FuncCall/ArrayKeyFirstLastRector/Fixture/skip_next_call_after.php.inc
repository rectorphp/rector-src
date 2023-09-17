<?php
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

function skipExecutionHasNextNext(array $arr) {
    reset($arr);
    while ($key !== null) {
        $key = key($arr);
        $value = current($arr);
        if ($key === null) {
            break;
        }
        echo $key . ": " . $value . PHP_EOL;
        next($arr);
    }
}

?>
