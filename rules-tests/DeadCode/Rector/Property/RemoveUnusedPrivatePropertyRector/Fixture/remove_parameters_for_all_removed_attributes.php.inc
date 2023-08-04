<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class RemoveParametersForAllRemovedAttributes
{
    /** @var string */
    private $thing1;

    /** @var string */
    private $thing2;

    /** @var string */
    private $thing3;

    /** @var string */
    private $thing4;

    /** @var string */
    private $thing5;

    /** @var string */
    private $thing6;

    public function __construct(
        string $thing1,
        string $thing2,
        string $thing3,
        string $thing4,
        string $thing5,
        string $thing6
    ) {
        $this->thing1 = $thing1;
        $this->thing2 = $thing2;
        $this->thing3 = $thing3;
        $this->thing4 = $thing4;
        $this->thing5 = $thing5;
        $this->thing6 = $thing6;
    }

    public function doSomethings()
    {
        echo $this->thing3;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class RemoveParametersForAllRemovedAttributes
{
    /** @var string */
    private $thing3;

    public function __construct(
        string $thing3,
    ) {
        $this->thing3 = $thing3;
    }

    public function doSomethings()
    {
        echo $this->thing3;
    }
}

?>
