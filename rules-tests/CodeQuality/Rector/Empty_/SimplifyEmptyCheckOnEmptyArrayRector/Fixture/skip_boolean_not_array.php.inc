<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

final class SkipBooleanNotArray
{
    public function verify($values): bool
    {
        return ! empty($values);
    }
}

?>
