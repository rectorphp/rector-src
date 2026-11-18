<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ThrowingConstructor
{
    /**
     * @throws \RuntimeException
     */
    public function __construct()
    {
        throw new \RuntimeException('boom');
    }
}

final class PropagatedFromConstructor
{
    public function run(): ThrowingConstructor
    {
        return new ThrowingConstructor();
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class ThrowingConstructor
{
    /**
     * @throws \RuntimeException
     */
    public function __construct()
    {
        throw new \RuntimeException('boom');
    }
}

final class PropagatedFromConstructor
{
    /**
     * @throws \RuntimeException
     */
    public function run(): ThrowingConstructor
    {
        return new ThrowingConstructor();
    }
}
