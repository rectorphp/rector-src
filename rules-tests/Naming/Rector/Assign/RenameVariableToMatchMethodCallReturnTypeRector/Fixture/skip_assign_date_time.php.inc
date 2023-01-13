<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Nette\Utils\DateTime;
use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\SameNameMethod;

final class SkipAssignDateTime
{
    public function run(SameNameMethod $sameNameMethod)
    {
        $createdAt = DateTime::from('now');
    }
}
