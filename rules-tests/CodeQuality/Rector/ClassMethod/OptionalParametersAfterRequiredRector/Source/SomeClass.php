<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Source;

class SomeClass
{
    public static function fromString(string $id) {}
    public static function fromInt(int $id) {}
}