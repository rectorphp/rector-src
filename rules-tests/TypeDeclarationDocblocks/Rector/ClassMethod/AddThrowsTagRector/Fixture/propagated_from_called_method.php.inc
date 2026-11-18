<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class PropagatedFromCalledMethod
{
    public function run(): void
    {
        $this->fail();
    }

    /**
     * @throws \RuntimeException
     */
    private function fail(): void
    {
        throw new \RuntimeException('boom');
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class PropagatedFromCalledMethod
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        $this->fail();
    }

    /**
     * @throws \RuntimeException
     */
    private function fail(): void
    {
        throw new \RuntimeException('boom');
    }
}
