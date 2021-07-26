<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\CompleteVarDocTypePropertyRector\Fixture;

class InvokableA {}
class InvokableB {}
class InvokableC {}
class FactoryA {}
class FactoryB {}
class FactoryC {}

final class SkipRecursiveMultipleClassStringArray
{
    /**
     * @var array<string, class-string[]>
     */
    public $services = [
        'invokables' => [
            InvokableA::class,
            InvokableB::class,
            InvokableC::class,
        ],
        'factories' => [
            FactoryA::class,
            FactoryB::class,
            FactoryC::class,
        ],
    ];
}

?>
