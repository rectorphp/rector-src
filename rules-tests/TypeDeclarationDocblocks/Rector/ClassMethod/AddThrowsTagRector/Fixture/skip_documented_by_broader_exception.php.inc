<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class SkipDocumentedByBroaderException
{
    /**
     * @throws \Exception
     */
    public function run(): void
    {
        throw new \RuntimeException('boom');
    }
}
