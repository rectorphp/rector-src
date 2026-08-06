<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipAsAssignVariable
{
    public function asAssign($result, $lastInsertedId)
    {
        $result[\array_key_first($result)]  = $lastInsertedId;
    }
}
