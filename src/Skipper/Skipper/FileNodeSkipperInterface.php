<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;

interface FileNodeSkipperInterface
{
    public function shouldSkip(string $fileName, ?Node $node): bool;
}
