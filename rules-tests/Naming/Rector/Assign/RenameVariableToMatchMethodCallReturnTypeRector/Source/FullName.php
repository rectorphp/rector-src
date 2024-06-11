<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source;

final class FullName
{
    public function getName(): string
    {
        return 'full name';
    }
}
