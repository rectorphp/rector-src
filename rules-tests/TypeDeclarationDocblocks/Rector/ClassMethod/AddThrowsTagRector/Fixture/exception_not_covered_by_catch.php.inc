<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ExceptionNotCoveredByCatch
{
    public function run(): void
    {
        try {
            throw new \RuntimeException('boom');
        } catch (\DomainException) {
        }
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ExceptionNotCoveredByCatch
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        try {
            throw new \RuntimeException('boom');
        } catch (\DomainException) {
        }
    }
}
