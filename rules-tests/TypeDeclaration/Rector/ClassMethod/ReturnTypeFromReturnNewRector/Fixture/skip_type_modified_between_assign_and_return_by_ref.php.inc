<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class SkipTypeModifiedBetweenAssignAndReturnByRef
{
    public function action()
    {
        $response = new SomeResponse();

        $this->verify($response);

        return $response;
    }

    private function verify(SomeResponse &$someResponse): void
    {
        if (is_cli()) {
            $someResponse = new CLIResponse();
        }
    }
}
