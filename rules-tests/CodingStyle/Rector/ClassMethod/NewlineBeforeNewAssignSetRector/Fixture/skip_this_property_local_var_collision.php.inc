<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class SkipThisPropertyLocalVarCollision
{
    public array $query;

    public string $another;

    public function __construct()
    {
        $query = [];
        $this->query = \is_array($query) ? $query : [];
        $this->another = 'string';
    }
}
