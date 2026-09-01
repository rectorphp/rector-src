<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipAsNestedAssign
{
    public function run(array $result, $lastInsertedId)
    {
        $result[\array_key_last($result)]['id'] = $lastInsertedId;
    }
}
