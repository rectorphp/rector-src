<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper\Custom;

use ReflectionClass;

interface ReflectionClassSkipperInterface extends CustomSkipperInterface
{
    public function skip(ReflectionClass $reflectionClass): bool;
}
