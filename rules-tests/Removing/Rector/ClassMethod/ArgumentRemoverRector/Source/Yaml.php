<?php

declare(strict_types=1);

namespace Rector\Tests\Removing\Rector\ClassMethod\ArgumentRemoverRector\Source;

class Yaml
{
    public const KEPT_CONSTANT = 2;

    public static function parse($input, $flags) {}
}
