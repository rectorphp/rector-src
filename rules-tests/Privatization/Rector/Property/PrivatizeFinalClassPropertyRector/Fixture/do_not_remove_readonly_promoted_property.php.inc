<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class DoNotRemoveReadonlyPromotedProperty
{
    public function __construct(protected readonly \stdClass $std)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class DoNotRemoveReadonlyPromotedProperty
{
    public function __construct(private readonly \stdClass $std)
    {
    }
}

?>
