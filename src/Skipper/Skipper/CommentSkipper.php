<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;

final readonly class CommentSkipper
{
    public function __construct(
        private SkipSkipper $skipSkipper
    ) {
    }

    public function shouldSkip(string $rectorClass, Node $node): bool
    {
        return $this->skipSkipper->doesMatchComments($rectorClass, $node);
    }
}
