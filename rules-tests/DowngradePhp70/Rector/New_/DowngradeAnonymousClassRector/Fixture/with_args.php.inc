<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

class WithArgs
{
    public function run($arg1, $arg2)
    {
        return new class ($arg1, $arg2) {
            public function execute()
            {
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

class WithArgs
{
    public function run($arg1, $arg2)
    {
        return new Anonymous__1524929001f1c36c7e4ce268f40d4c0a__0($arg1, $arg2);
    }
}
class Anonymous__1524929001f1c36c7e4ce268f40d4c0a__0
{
    public function execute()
    {
    }
}
