<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipVoter;

use PhpParser\Node;
use Rector\Skipper\Contract\SkipVoterInterface;
use Rector\Skipper\Skipper\SkipSkipper;

final readonly class CommentSkipVoter implements SkipVoterInterface
{
    public function __construct(
        private SkipSkipper $skipSkipper
    ) {
    }

    public function match(string | object $element): bool
    {
        return is_object($element);
    }

    public function shouldSkip(string | object $element, string $filePath, ?Node $node): bool
    {
        return $this->skipSkipper->doesMatchComments($element, $filePath, $node ? $node->getComments() : []);
    }
}
