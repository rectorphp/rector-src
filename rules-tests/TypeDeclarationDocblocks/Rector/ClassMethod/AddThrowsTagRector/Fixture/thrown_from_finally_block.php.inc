<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ThrownFromFinallyBlock
{
    public function run(): void
    {
        try {
        } catch (\RuntimeException) {
        } finally {
            throw new \RuntimeException('boom');
        }
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ThrownFromFinallyBlock
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        try {
        } catch (\RuntimeException) {
        } finally {
            throw new \RuntimeException('boom');
        }
    }
}
