<?php

function foo()
{
    return 'foo';
}

echo foo();

?>
-----
<?php

declare(strict_types=1);

function foo()
{
    return 'foo';
}

echo foo();

?>