<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

final class AllowDateSuffix
{
    public function run()
    {
        $someDate = new \DateTime('...');
    }
}
