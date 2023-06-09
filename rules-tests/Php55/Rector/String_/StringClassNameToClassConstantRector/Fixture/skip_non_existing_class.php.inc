<?php

declare(strict_types=1);

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SkipNonExistingClass
{
    public function run()
    {
        return 'Not\Existing\Everything';
    }
}
