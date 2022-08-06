<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\AnonymousClass;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\AnonymousClass\SomeEventDispatcher;

final class AddAnonymousClassExtendsType
{
    public function getEventDispatcher()
    {
        return new class extends SomeEventDispatcher {
            public function dispatch(object $event, string $eventName = null): object
            {
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\AnonymousClass;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\AnonymousClass\SomeEventDispatcher;

final class AddAnonymousClassExtendsType
{
    public function getEventDispatcher(): \Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\AnonymousClass\SomeEventDispatcher
    {
        return new class extends SomeEventDispatcher {
            public function dispatch(object $event, string $eventName = null): object
            {
            }
        };
    }
}

?>
