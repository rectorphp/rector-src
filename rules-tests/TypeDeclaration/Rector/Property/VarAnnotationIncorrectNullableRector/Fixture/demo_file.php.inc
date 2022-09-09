<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class DemoFile
{
    /** @var \Closure(string): string */
    private ?\Closure $tooltipGenerator = null;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class DemoFile
{
    /** @var null|\Closure(string): string */
    private ?\Closure $tooltipGenerator = null;
}

?>
