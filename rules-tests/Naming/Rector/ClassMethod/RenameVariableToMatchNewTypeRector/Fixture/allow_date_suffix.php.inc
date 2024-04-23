<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

final class AlloweDateSuffix
{
    public function run()
    {
        $someDate = new \DateTime('...');
    }
}
