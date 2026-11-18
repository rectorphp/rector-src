<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class RethrownFromCatchBlock
{
    public function run(): void
    {
        try {
            throw new \RuntimeException('boom');
        } catch (\RuntimeException $runtimeException) {
            throw $runtimeException;
        }
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class RethrownFromCatchBlock
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        try {
            throw new \RuntimeException('boom');
        } catch (\RuntimeException $runtimeException) {
            throw $runtimeException;
        }
    }
}
