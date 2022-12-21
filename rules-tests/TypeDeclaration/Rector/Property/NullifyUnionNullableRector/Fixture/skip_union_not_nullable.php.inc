<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\NullifyUnionNullableRector\Fixture;

use stdClass;

final class SkipUnionNotNullable
{
    private string|stdClass $property;
}
