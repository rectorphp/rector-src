<?php

namespace Rector\Tests\CodingStyle\Rector\Property\SplitGroupedPropertiesRector\Fixture;

final class WithValues
{
    /**
     * @var string
     */
    public $isIt = true, $isIsThough = false;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\SplitGroupedPropertiesRector\Fixture;

final class WithValues
{
    /**
     * @var string
     */
    public $isIt = true;
    /**
     * @var string
     */
    public $isIsThough = false;
}

?>
