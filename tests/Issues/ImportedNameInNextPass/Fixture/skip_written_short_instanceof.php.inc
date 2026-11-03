<?php

namespace Rector\Tests\Issues\ImportedNameInNextPass\Fixture;

use Rector\Tests\Issues\ImportedNameInNextPass\Source\Directory;

final class SkipWrittenShortInstanceof
{
    public function run(?Directory $directory): void
    {
        if (! $directory instanceof Directory) {
            return;
        }

        echo $directory->value;
    }
}
