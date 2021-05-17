<?php

namespace Rector\Tests\Renaming\Rector\FuncCall\RenameFunctionWithRemoveDeadStmtRector\Fixture;

class Fixture
{
    public function someMethod()
    {
        preg_replace('//', 'foo', 'bar');
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\FuncCall\RenameFunctionWithRemoveDeadStmtRector\Fixture;

class Fixture
{
    public function someMethod()
    {
        \Safe\preg_replace()('//', 'foo', 'bar');
    }
}

?>
