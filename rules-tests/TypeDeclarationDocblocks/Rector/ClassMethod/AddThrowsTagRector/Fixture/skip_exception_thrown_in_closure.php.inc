<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class SkipExceptionThrownInClosure
{
    public function run(): \Closure
    {
        return static function (): void {
            throw new \RuntimeException('boom');
        };
    }
}
