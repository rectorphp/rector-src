<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponseInterface;

final class ReturnAnonymousClassImplements
{
    public function action()
    {
        return new class implements SomeResponseInterface {};
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponseInterface;

final class ReturnAnonymousClassImplements
{
    public function action(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponseInterface
    {
        return new class implements SomeResponseInterface {};
    }
}

?>
