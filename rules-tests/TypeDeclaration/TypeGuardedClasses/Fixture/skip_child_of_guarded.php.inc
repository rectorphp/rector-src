<?php

namespace Rector\Tests\TypeDeclaration\TypeGuardedClasses\Fixture;

use Rector\Tests\TypeDeclaration\TypeGuardedClasses\Source\GuardedRepository;

class SkipChildOfGuarded extends GuardedRepository
{
    public function getData()
    {
        return [];
    }
}
