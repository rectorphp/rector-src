<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

final class SkipMultiStmtsGetter
{
    private string $name;

    public function getName(): string
    {
        $item = 1000;

        return $this->name;
    }
}
