<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClassAndOther
{
    public function action()
    {
        if (rand(0, 1)) {
            return new class {};
        }

        return new static();
    }
}
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClassAndOther
{
    public function action(): object
    {
        if (rand(0, 1)) {
            return new class {};
        }

        return new static();
    }
}
