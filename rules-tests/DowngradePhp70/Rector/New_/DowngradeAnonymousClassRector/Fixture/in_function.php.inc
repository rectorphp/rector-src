<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

function inFunction()
{
    return new class {
            public function execute()
            {
            }
    };
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

function inFunction()
{
    return new Anonymous__a598eff560ff9cabff1daddf5afd8e31__0();
}
class Anonymous__a598eff560ff9cabff1daddf5afd8e31__0
{
    public function execute()
    {
    }
}
