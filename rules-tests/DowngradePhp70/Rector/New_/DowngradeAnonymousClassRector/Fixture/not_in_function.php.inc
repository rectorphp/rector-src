<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

return new class {
    public function execute()
    {
    }
};

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

return new Anonymous__4d01ff360d2ab1a3d76c31180eb42d6b__0();
class Anonymous__4d01ff360d2ab1a3d76c31180eb42d6b__0
{
    public function execute()
    {
    }
}
