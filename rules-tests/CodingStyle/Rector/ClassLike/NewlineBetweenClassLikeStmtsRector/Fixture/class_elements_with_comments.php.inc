<?php

namespace Rector\Tests\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector\Fixture;

final class ClassElementsWithComments
{
    /**
     * @var string
     */
    private $property;
    /**
     * @var string
     */
    private $property2;
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

final class ClassElementsWithComments
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $property2;

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
