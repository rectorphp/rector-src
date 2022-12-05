<?php

namespace Rector\Tests\CodingStyle\Rector\Property\SplitGroupedPropertiesRector\Fixture;

class Fixture
{
    /**
     * @var string
     */
    public $isIt, $isIsThough;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\SplitGroupedPropertiesRector\Fixture;

class Fixture
{
    /**
     * @var string
     */
    public $isIt;
    /**
     * @var string
     */
    public $isIsThough;
}

?>
