<?php

namespace Rector\Tests\Php70\Rector\FuncCall\MultiDirnameRector\Fixture;

function multiDirname()
{
    dirname(dirname($path, $level));

    dirname("foo/" . dirname($path));

    dirname(dirname($path) . $foo);

    foo\dirname(dirname($path));

    dirname($path, 1);

    dirname($path, 2);

    dirname(getcwd(), 2);
}

?>
