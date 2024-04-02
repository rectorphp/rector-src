<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyDocRector\Fixture;

class SkipNoReadonlyDoc
 {
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
