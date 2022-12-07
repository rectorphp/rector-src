<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipBooleanNotNotArray
{
    public function verify($values): bool
    {
        return ! empty($values);
    }
}

?>
