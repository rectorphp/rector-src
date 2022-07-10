<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class SkipTypeModifiedBetweenAssignAndReturnWithIf
{
    public function action()
    {
        $response = new SomeResponse();

        if (is_cli()) {
            $someResponse = new CLIResponse();
        }

        return $response;
    }
}
