<?php

function simplifyConditions()
{
    $foo = 'start';
    if (!($foo !== 'bar')) {
    } elseif (!($foo != 'bug')) {
    }

    function foo(int $bar)
    {
        return !($bar > 0);
    }

    $baz = !($foo === 'baz');

    function bar(int $foo)
    {
        return !($foo >= 0);
    }
}

?>
-----
<?php

function simplifyConditions()
{
    $foo = 'start';
    if ($foo === 'bar') {
    } elseif ($foo == 'bug') {
    }

    function foo(int $bar)
    {
        return $bar <= 0;
    }

    $baz = $foo !== 'baz';

    function bar(int $foo)
    {
        return $foo < 0;
    }
}

?>
