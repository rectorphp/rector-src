<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

interface CallerProvider
{
    /**
     * @throws \RuntimeException
     */
    public function get(): int;
}

final class PropagatedFromInterfaceMethodOnProperty
{
    public function __construct(
        private CallerProvider $callerProvider,
    ) {}

    public function run(): int
    {
        return $this->callerProvider->get();
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

interface CallerProvider
{
    /**
     * @throws \RuntimeException
     */
    public function get(): int;
}

final class PropagatedFromInterfaceMethodOnProperty
{
    public function __construct(
        private CallerProvider $callerProvider,
    ) {}

    /**
     * @throws \RuntimeException
     */
    public function run(): int
    {
        return $this->callerProvider->get();
    }
}
