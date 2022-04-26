<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

class Fixture
{
    public function run()
    {
        return new class {
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

class Fixture
{
    public function run()
    {
        return new Anonymous__4ca3de28f19d52cd0bb75d0880ce939c__0();
    }
}
class Anonymous__4ca3de28f19d52cd0bb75d0880ce939c__0
{
    public function execute()
    {
    }
}
