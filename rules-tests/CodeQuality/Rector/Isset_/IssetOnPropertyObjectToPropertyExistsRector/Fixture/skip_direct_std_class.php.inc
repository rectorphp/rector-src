<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

use stdClass;

final class SkipDirectStdClass
{
    public function x(stdClass $value) {
        isset($value->property);
    }
}
