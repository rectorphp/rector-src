<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipArrayDimFetchAssign
{
    private array $default = [
        'name' => 'some name',
        'tplset' => null,
    ];

    public function __construct()
    {
        $this->default['tplset'] = 'dotty';
    }
}
