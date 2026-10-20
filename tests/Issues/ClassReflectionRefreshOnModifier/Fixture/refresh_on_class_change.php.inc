<?php

namespace Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Fixture;

class RefreshOnClassChange
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Fixture;

final readonly class RefreshOnClassChange
{
    public function run()
    {
    }
}

?>
