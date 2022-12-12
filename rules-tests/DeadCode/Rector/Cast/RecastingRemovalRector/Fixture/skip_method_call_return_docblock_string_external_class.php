<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

use Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source\ExternalClass;

class SkipMethodCallReturnDocblockStringExternalClass
{
    public function __construct(private readonly ExternalClass $externalClass)
    {
    }

    public function run(): string
    {
        return (string) $this->externalClass->getResult();
    }
}
