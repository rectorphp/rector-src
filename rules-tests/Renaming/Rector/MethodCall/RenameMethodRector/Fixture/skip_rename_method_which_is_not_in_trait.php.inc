<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\SomeTrait;

final class SkipRenameMethodWhichIsNotInTrait
{
    use SomeTrait;

    public function _otherMethod()
    {
    }
}
