<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source;

final class FastRunner
{
    public function run()
    {
        return $this;
    }
}
