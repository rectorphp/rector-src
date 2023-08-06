<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClass
{
    public function action()
    {
        return new class {};
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClass
{
    public function action(): object
    {
        return new class {};
    }
}

?>
