<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PhpParser\Node\Name\FullyQualified;

class ReplaceAttribute
{
    public function __construct(
        public FullyQualified $original,
        public FullyQualified $replacement
    ) {
    }
}
