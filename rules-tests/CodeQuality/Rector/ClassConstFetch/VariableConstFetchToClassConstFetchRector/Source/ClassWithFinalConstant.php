<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source;

class ClassWithFinalConstant
{
    public final const NAME = 'SomeName';

    /**
     * @final
     */
    public const DOCBLOCK_FINAL = 'SomeDocblockFinal';
}
