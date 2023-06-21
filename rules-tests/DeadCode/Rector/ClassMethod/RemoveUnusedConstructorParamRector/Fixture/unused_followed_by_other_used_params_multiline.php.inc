<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class UnusedFollowedByOtherUsedParamsMultiline
{
    private ExampleOne $paramOne;
    private ExampleTwo $paramTwo;
    private ExampleThree $paramThree;

    public function __construct(
        ExampleOne $paramOne,
        ExampleTwo $paramTwo,
        ExampleThree $paramThree
    ) {
        $this->paramOne = $paramOne;
        $this->paramThree = $paramThree;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class UnusedFollowedByOtherUsedParamsMultiline
{
    private ExampleOne $paramOne;
    private ExampleTwo $paramTwo;
    private ExampleThree $paramThree;

    public function __construct(
        ExampleOne $paramOne,
        ExampleThree $paramThree
    ) {
        $this->paramOne = $paramOne;
        $this->paramThree = $paramThree;
    }
}

?>
