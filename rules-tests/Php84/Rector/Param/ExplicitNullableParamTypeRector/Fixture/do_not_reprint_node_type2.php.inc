<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

use DateTime;
use stdClass;

class DoNotReprintNodeType2
{
    public function run(stdClass|DateTime $a = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

use DateTime;
use stdClass;

class DoNotReprintNodeType2
{
    public function run(stdClass|DateTime|null $a = null)
    {
    }
}

?>
