<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source;

final class AnotherObject
{
    public function equals(AnotherObject $anotherObject)
    {
        return (bool) rand(0, 1);
    }
}
