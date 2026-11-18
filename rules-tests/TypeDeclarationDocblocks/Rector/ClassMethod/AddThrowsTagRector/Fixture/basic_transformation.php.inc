<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class BasicTransformation
{
    public function run(): void
    {
        throw new \RuntimeException('boom');
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class BasicTransformation
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        throw new \RuntimeException('boom');
    }
}
