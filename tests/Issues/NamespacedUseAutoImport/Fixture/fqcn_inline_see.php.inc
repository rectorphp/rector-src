<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Tests\Issues\NamespacedUse\Source\SomeClass;

final class FqcnInlineSee
{
    /**
     * See {@see \Rector\Tests\Issues\NamespacedUse\Source\SomeClass::$property} for more information.
     */
    public function test(): void {}
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

final class FqcnInlineSee
{
    /**
     * See {@see \Rector\Tests\Issues\NamespacedUse\Source\SomeClass::$property} for more information.
     */
    public function test(): void {}
}

?>
