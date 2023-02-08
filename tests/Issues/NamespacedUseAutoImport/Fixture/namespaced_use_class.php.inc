<?php

namespace Rector\Core\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Core\Tests\Issues\NamespacedUseAutoImport\Source;

final class NamespacedUse extends Source\SomeClass
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Core\Tests\Issues\NamespacedUseAutoImport\Source\SomeClass;

final class NamespacedUse extends SomeClass
{
}

?>
