<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class SkipMultipleCasts
{
    public function run($data)
    {
        if (rand(0, 1)) {
            return (string) $data;
        }

        return (bool) $data;
    }
}