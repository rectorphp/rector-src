<?php

namespace Rector\Tests\Php70\Rector\FuncCall\MultiDirnameRector\Fixture;

function multiDirname()
{
    dirname(dirname($path));

    new dirname(dirname(dirname($path)));
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\MultiDirnameRector\Fixture;

function multiDirname()
{
    dirname($path, 2);

    new dirname(dirname($path, 2));
}

?>
