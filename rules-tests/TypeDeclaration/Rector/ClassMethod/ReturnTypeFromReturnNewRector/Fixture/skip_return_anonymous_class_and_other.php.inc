<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class SkipReturnAnonymousClassAndOther
{
    public function action()
    {
        if (rand(0, 1)) {
            return new class {};
        }

        return new static();
    }
}
