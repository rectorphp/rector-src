<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\AnonymousClass;

use Psr\EventDispatcher\EventDispatcherInterface;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\AnonymousClass\SomeEventDispatcher;

final class GenericObjectWhenDoubleTypes
{
    public function getEventDispatcher()
    {
        return new class extends SomeEventDispatcher implements EventDispatcherInterface {
            public function dispatch(object $event, string $eventName = null): object
            {
            }
        };
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\AnonymousClass;

use Psr\EventDispatcher\EventDispatcherInterface;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\AnonymousClass\SomeEventDispatcher;

final class GenericObjectWhenDoubleTypes
{
    public function getEventDispatcher(): object
    {
        return new class extends SomeEventDispatcher implements EventDispatcherInterface {
            public function dispatch(object $event, string $eventName = null): object
            {
            }
        };
    }
}
