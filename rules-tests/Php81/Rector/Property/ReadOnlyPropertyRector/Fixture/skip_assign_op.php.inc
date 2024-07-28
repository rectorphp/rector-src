<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipAssignOp
{
    private string $name;
    private int $count;

    public function __construct(string $name, bool $flag = false)
    {
        $this->name = $name;

        if ($flag) {
            $this->name .= 'changed';
        }

        $this->count = 0;
        if ($flag) {
            ++$this->count;
        }
    }
}
