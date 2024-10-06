<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class PromotedProperty
{
    public function __construct(protected $value)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class PromotedProperty
{
    public function __construct(private $value)
    {
    }
}

?>
