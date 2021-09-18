<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixturePrivatePropertyOnly;

final class Fixture
{
    /**
     * @var bool
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

final class Fixture
{
    private bool $privateProperty;

    /**
     * @var bool
     */
    public $publicProperty;
}

?>
