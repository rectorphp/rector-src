<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

function () {
    return new class {
            public function execute()
            {
            }
    };
};

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

function () {
    return new Anonymous__d746a33921816ede30ef66b0a30f1039__0();
};
class Anonymous__d746a33921816ede30ef66b0a30f1039__0
{
    public function execute()
    {
    }
}
