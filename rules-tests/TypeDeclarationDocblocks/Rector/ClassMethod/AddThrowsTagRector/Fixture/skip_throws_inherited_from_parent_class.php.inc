<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

abstract class ParentWithThrows
{
    /**
     * @throws \RuntimeException
     */
    abstract public function start(): void;
}

final class SkipThrowsInheritedFromParentClass extends ParentWithThrows
{
    #[\Override]
    public function start(): void
    {
        throw new \RuntimeException('boom');
    }
}
