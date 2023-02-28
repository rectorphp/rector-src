<?php

namespace Rector\Tests\Php71\Rector\ClassConst\PublicConstantVisibilityRector\Fixture;

class PublicVisibilityFinalConstant
{
    const EXIT_OK = 0;
    final const EXIT_KO = 1;
    final const EXIT_NULL = 2;
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\ClassConst\PublicConstantVisibilityRector\Fixture;

class PublicVisibilityFinalConstant
{
    public const EXIT_OK = 0;
    final public const EXIT_KO = 1;
    final public const EXIT_NULL = 2;
}

?>
