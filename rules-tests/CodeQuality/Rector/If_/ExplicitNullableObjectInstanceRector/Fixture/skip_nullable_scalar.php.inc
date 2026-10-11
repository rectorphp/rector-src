<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class SkipNullableScalar
{
    public function run(?string $item)
    {
        if (!$item) {
            return 'empty';
        }

        if ($item === null) {
            return 'empty';
        }
    }
}
