<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipValidationGroupsKeyedArray
{
    public function run()
    {
        $config = [
            'validation_groups' => [$this, 'name'],
        ];
    }

    public function name()
    {
    }
}
