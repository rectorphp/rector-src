<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

final class SkipSelf
{
    public function run()
    {
        $someType = new self();
    }
}
