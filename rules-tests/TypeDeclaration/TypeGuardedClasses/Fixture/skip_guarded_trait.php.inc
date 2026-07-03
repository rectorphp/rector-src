<?php

namespace Rector\Tests\TypeDeclaration\TypeGuardedClasses\Fixture;

trait SkipGuardedTrait
{
    public function resolveItem($item)
    {
        return $item['name'];
    }
}
