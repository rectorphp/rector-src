<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source;

final class ExternalStrictReturnType
{
    public function getResult(): string
    {
        return 'test';
    }
}
