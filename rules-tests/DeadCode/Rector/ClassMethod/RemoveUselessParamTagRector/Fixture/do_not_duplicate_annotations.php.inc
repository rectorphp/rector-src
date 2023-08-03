<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class DoNotDuplicateAnnotations
{
    /**
     * @ApiDoc(
     *   description = "Some Description"
     *
     * )
     *
     * @Route("/some/route")
     *
     * @param string $thing
     */
    public function foo(string $thing)
    {
        echo $thing;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class DoNotDuplicateAnnotations
{
    /**
     * @ApiDoc(
     *   description = "Some Description"
     *
     * )
     *
     * @Route("/some/route")
     */
    public function foo(string $thing)
    {
        echo $thing;
    }
}

?>
