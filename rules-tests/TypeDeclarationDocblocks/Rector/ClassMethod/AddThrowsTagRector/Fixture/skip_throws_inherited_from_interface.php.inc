<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

interface WorkflowStarter
{
    /**
     * @throws \RuntimeException
     */
    public function start(): void;
}

final class SkipThrowsInheritedFromInterface implements WorkflowStarter
{
    #[\Override]
    public function start(): void
    {
        throw new \RuntimeException('boom');
    }
}
