<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\EliteManager;

final class SkipPublicProperty
{
    public function __construct(
        public EliteManager $entityMessenger
    ) {
    }
}
