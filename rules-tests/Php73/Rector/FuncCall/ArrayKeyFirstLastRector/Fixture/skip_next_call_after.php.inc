<?php

function a(array $arr) {
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
