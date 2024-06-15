<?php

namespace Rector\Tests\Php74\Rector\FuncCall\RestoreIncludePathToIniRestoreRector\Fixture;

class Fixture
{
    public function run()
    {
        restore_include_path();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\RestoreIncludePathToIniRestoreRector\Fixture;

class Fixture
{
    public function run()
    {
        ini_restore('include_path');
    }
}

?>
