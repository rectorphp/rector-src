<?php

namespace Rector\Core\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Foo2;

final class SkipNamespacedUseSingleNamespace
{
    public function __construct()
    {
        new Foo2\Storage();
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Foo2\Storage;
use Foo2;

final class SkipNamespacedUseSingleNamespace
{
    public function __construct()
    {
        new Storage();
    }
}

?>
