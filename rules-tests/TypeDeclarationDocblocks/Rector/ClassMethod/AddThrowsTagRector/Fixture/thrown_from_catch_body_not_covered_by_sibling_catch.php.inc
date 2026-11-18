<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ThrownFromCatchBodyNotCoveredBySiblingCatch
{
    public function run(): void
    {
        try {
        } catch (\DomainException) {
            throw new \RuntimeException('boom');
        } catch (\RuntimeException) {
        }
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ThrownFromCatchBodyNotCoveredBySiblingCatch
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        try {
        } catch (\DomainException) {
            throw new \RuntimeException('boom');
        } catch (\RuntimeException) {
        }
    }
}
