<?php

// no namespace on purpose to cover crash on FileWithoutNamespace
exit();

// this part is needed to produce crash
echo 'some statement';

function run2()
{
    preg_split('#a#', null);
}

?>
-----
<?php

// no namespace on purpose to cover crash on FileWithoutNamespace
exit();

// this part is needed to produce crash
echo 'some statement';

function run2()
{
    preg_split('#a#', '');
}

?>
