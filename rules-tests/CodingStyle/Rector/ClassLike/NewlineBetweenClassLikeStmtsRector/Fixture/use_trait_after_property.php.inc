<?php

namespace Rector\Tests\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector\Fixture;

class UseTraitAfterProperty
{
    private $a;
    use SomeTrait;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector\Fixture;

class UseTraitAfterProperty
{
    private $a;

    use SomeTrait;
}

?>