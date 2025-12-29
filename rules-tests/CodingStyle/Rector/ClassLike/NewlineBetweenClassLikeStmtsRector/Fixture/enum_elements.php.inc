<?php

namespace Rector\Tests\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector\Fixture;

enum EnumElements
{
    use SomeTrait;
    case One;
    /**
     * a method
     */
    public function run()
    {
    }
    /**
     * a method
     */
    public function run2()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector\Fixture;

enum EnumElements
{
    use SomeTrait;

    case One;

    /**
     * a method
     */
    public function run()
    {
    }

    /**
     * a method
     */
    public function run2()
    {
    }
}

?>
