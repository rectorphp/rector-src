<?php

namespace Rector\Core\Tests\Issues\AliasedImportDouble\Fixture;

final class Anything
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AliasedImportDouble\Fixture;

final class Anything
{
    public function go()
    {
    }
}

?>
