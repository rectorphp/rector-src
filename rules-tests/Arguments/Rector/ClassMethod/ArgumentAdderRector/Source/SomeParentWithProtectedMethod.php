<?php

declare(strict_types=1);

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source;

use stdClass;

class SomeParentWithProtectedMethod
{
    protected function run(mixed $value, ?stdClass $element, bool $inline): string
    {
        return '';
    }
}