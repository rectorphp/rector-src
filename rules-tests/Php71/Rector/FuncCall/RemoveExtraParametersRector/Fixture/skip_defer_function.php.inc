<?php

// no namespace on purpose to demonstrate the issue
function defer(string $foo, callable $callback): void
{
    $callback($foo);
}

defer("foo", static function ($bar) {
    echo $bar;
});
