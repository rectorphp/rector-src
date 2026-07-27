<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipNestedArrayDimFetchAssign
{
    private array $config = [
        'first' => ['second' => null],
    ];

    public function __construct()
    {
        $this->config['first']['second'] = 'value';
    }
}
