<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

class RefreshOnClassChange
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

final readonly class RefreshOnClassChange
{
    public function run()
    {
    }
}

?>
