<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\Fixture;

use stdClass;

final class SkipNewOtherObject
{
    public function test()
    {
        return new stdClass();
    }
}
