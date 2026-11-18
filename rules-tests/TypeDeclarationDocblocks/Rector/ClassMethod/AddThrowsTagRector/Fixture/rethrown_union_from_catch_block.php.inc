<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class RethrownUnionFromCatchBlock
{
    public function run(): void
    {
        try {
            $this->work();
        } catch (\RuntimeException | \JsonException $exception) {
            throw $exception;
        }
    }

    /**
     * @throws \RuntimeException
     * @throws \JsonException
     */
    private function work(): void
    {
        throw new \RuntimeException('boom');
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class RethrownUnionFromCatchBlock
{
    /**
     * @throws \RuntimeException
     * @throws \JsonException
     */
    public function run(): void
    {
        try {
            $this->work();
        } catch (\RuntimeException | \JsonException $exception) {
            throw $exception;
        }
    }

    /**
     * @throws \RuntimeException
     * @throws \JsonException
     */
    private function work(): void
    {
        throw new \RuntimeException('boom');
    }
}
