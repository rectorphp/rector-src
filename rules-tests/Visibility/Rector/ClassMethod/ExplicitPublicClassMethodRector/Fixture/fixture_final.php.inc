<?php

namespace Rector\Tests\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector\Fixture;

final class SomeFinalClass
{
    final function noExplicitVisFinal()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector\Fixture;

final class SomeFinalClass
{
    final public function noExplicitVisFinal()
    {
    }
}

?>
