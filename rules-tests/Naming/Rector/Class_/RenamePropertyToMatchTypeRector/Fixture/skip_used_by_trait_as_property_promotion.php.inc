<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\EliteManager;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\UseEliteManagerTrait;

class SkipUsedByTraitAsPropertyPromotion
{
    use UseEliteManagerTrait;

    public function __construct(private EliteManager $eventManager)
    {
    }
}
