<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class TernaryNullable
{
    public function run(?\stdClass $item): string
    {
        return $item ? 'yes' : 'no';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class TernaryNullable
{
    public function run(?\stdClass $item): string
    {
        return $item instanceof \stdClass ? 'yes' : 'no';
    }
}

?>
