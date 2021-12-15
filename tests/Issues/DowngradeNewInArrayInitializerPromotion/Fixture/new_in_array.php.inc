<?php

namespace Rector\Core\Tests\Issues\DowngradeNewInArrayInitializerPromotion\Fixture;

use stdClass;

class NewInArray
{
    public function __construct(public array $property = [
        'a' => new stdClass()
    ])
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DowngradeNewInArrayInitializerPromotion\Fixture;

use stdClass;

class NewInArray
{
    public $property;
    public function __construct(array $property = null)
    {
        $this->property = $property ?? [
            'a' => new stdClass()
        ];
    }
}

?>
