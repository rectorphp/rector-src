<?php

namespace Rector\Tests\CodingStyle\Rector\Property\NullifyUnionNullableRector\Fixture;

use stdClass;

final class SkipUnionNotNullable
{
    private string|stdClass $property;
}
