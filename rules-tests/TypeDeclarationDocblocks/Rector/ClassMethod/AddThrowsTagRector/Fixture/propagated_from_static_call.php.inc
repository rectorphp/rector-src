<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class StaticThrower
{
    /**
     * @throws \RuntimeException
     */
    public static function fail(): void
    {
        throw new \RuntimeException('boom');
    }
}

final class PropagatedFromStaticCall
{
    public function run(): void
    {
        StaticThrower::fail();
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class StaticThrower
{
    /**
     * @throws \RuntimeException
     */
    public static function fail(): void
    {
        throw new \RuntimeException('boom');
    }
}

final class PropagatedFromStaticCall
{
    /**
     * @throws \RuntimeException
     */
    public function run(): void
    {
        StaticThrower::fail();
    }
}
