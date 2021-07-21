<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Source\ATrait;

class SkipUsedByTrait
{
    use ATrait;

    private $aProperty;
}
