<?php

function run()
{
    (yield 1) and true;
    (yield from [1, 2]) and true;
}

?>
-----
<?php

function run()
{
    (yield 1) && true;
    (yield from [1, 2]) && true;
}

?>
