<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class RemoveFinalModifier
{
    protected final $x = true;
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class RemoveFinalModifier
{
    private $x = true;
}

?>
