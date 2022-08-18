<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\AnonymousClass;

use Psr\EventDispatcher\EventDispatcherInterface;

final class AddAnonymousClassImplementsType
{
    public function getEventDispatcher()
    {
        return new class implements EventDispatcherInterface {
            public function dispatch(object $event): void
            {
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\AnonymousClass;

use Psr\EventDispatcher\EventDispatcherInterface;

final class AddAnonymousClassImplementsType
{
    public function getEventDispatcher(): \Psr\EventDispatcher\EventDispatcherInterface
    {
        return new class implements EventDispatcherInterface {
            public function dispatch(object $event): void
            {
            }
        };
    }
}

?>
