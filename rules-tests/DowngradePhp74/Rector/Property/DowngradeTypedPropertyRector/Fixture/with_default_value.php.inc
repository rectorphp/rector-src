<?php

namespace Rector\Tests\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector\Fixture;

class WithDefaultValue
{
    protected ?string $test = "test";
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector\Fixture;

class WithDefaultValue
{
    /**
     * @var string|null
     */
    protected $test = "test";
}

?>
