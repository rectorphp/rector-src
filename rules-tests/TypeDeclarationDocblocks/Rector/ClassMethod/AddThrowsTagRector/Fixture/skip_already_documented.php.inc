<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class SkipAlreadyDocumented
{
    /**
     * @throws \RuntimeException when it fails
     */
    public function run(): void
    {
        throw new \RuntimeException('boom');
    }
}
