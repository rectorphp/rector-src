<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class SkipPossibleVoidWithEarlyReturn
{
    public function bar()
    {
        if (random_int(0,1)) {
            return;
        }

        return new self();
    }
}
