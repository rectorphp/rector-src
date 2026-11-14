<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Tests\Issues\NamespacedUseAutoImport\Source;

final class NamespacedUse extends Source\SomeClass
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Tests\Issues\NamespacedUseAutoImport\Source\SomeClass;

final class NamespacedUse extends SomeClass
{
}

?>
