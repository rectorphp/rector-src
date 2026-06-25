<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Source;

abstract class BaseWithPropertyDocblock
{
    /**
     * @var list<literal-string>
     */
    public array $myArray = ['a', 'b', 'c'];
}
