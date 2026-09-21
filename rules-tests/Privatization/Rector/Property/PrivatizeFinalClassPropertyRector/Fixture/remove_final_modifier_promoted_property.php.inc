<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class RemoveFinalModifierPromotedProperty
{
    public function __construct(protected final $value)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class RemoveFinalModifierPromotedProperty
{
    public function __construct(private $value)
    {
    }
}

?>
