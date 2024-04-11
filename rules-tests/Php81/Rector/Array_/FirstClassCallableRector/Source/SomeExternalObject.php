<?php
declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source;

final class SomeExternalObject
{
    public function sleepOver()
    {
    }

    public static function sleepStatic()
    {
    }

    private static function sleepPrivateStatic()
    {
    }

    public function __invoke(bool $param)
    {
        return $param === true;
    }
}
