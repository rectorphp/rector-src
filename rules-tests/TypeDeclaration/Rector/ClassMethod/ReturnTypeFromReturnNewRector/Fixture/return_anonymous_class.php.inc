<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClass
{
    public function action()
    {
        return new class {};
    }

    public function foo()
    {
        $data = new class {};

        return $data;
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

    public function foo(): object
    {
        $data = new class {};

        return $data;
    }
}

?>
