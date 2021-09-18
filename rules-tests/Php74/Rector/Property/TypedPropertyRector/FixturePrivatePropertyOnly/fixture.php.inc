<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixturePrivatePropertyOnly;

use Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Source\AnotherClass;

final class Fixture
{
    /**
     * @var AnotherClass
     */
    private $privateProperty;

    /**
     * @var bool
     */
    public $publicProperty;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixturePrivatePropertyOnly;

use Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Source\AnotherClass;

final class Fixture
{
    private AnotherClass $privateProperty;

    /**
     * @var bool
     */
    public $publicProperty;
}

?>
