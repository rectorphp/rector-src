<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class SkipDoubleAssignedToVariable
{
    public function action()
    {
        $ambiguousResponse = new SomeResponse();

        $ambiguousResponse = 5000;

        return $ambiguousResponse;
    }
}
