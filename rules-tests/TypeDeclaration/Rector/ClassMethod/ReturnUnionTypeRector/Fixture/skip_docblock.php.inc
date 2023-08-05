<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

final class SkipDocblock
{
    /**
     * @return null|stdClass
     */
    public function run()
    {
    }
}
