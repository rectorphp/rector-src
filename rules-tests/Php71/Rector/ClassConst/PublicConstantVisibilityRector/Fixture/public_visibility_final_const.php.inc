<?php

namespace Rector\Tests\Php71\Rector\ClassConst\PublicConstantVisibilityRector\Fixture;

class PublicVisibilityFinalConstant
{
    final const EXIT_KO = 1;
    final const EXIT_NULL = 2;
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\ClassConst\PublicConstantVisibilityRector\Fixture;

class PublicVisibilityFinalConstant
{
    final public const EXIT_KO = 1;
    final public const EXIT_NULL = 2;
}

?>
