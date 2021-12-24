<?php

namespace Rector\Tests\Renaming\Rector\ConstFetch\RenameConstantRector\Fixture;

use Rector\Tests\Renaming\Rector\ConstFetch\RenameConstantRector\Source\ExternalList;

final class ForeachLiteral
{
    public function run(): array
    {
        $map = [];
        foreach (ExternalList::VALUES as $externalValue) {
            $map[$externalValue] = 100;
        }
        return $map;
    }
}
