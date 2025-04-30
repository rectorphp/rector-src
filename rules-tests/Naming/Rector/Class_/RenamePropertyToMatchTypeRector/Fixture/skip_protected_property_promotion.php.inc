<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper;

class SkipProtectedPropertyPromotionOnNonFinalClass
{
    public function __construct(protected GitWrapper $wrapper)
    {
    }
}
