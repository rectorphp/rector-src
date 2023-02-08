<?php

namespace Rector\Core\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Foo2\Storage;
use Foo2;

final class NamespacedUseSingleNamespaceImported
{
    public function __construct()
    {
        new Storage();
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Foo2\Storage;

final class NamespacedUseSingleNamespaceImported
{
    public function __construct()
    {
        new Storage();
    }
}

?>
