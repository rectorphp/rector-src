<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class SkipExceptionCaughtInMethod
{
    public function run(): void
    {
        try {
            throw new \RuntimeException('boom');
        } catch (\RuntimeException) {
        }
    }

    public function caughtByParentClass(): void
    {
        try {
            throw new \RuntimeException('boom');
        } catch (\Exception) {
        }
    }
}
