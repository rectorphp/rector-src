<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

/**
 * @immutable
 */
final class SkipReadonlyPhpdocClass
{
    public string|null $display;
}
