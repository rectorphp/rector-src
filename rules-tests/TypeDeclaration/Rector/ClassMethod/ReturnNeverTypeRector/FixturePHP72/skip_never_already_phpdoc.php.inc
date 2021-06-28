<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

final class SkipNeverAlready
{
    /**
     * @return never
     */
    public function run()
    {
        throw new InvalidException();
    }
}
