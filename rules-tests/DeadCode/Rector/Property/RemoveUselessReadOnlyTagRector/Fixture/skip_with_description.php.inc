<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector\Fixture;

class SkipHasDescription
 {
    /**
     * @readonly some desc
     */
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
