<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\ProcessAnalyzer\RectifiedAnalyzer;
use Rector\Skipper\Contract\SkipVoterInterface;
use Webmozart\Assert\Assert;

/**
 * @api
 * @see \Rector\Tests\Skipper\Skipper\SkipperTest
 */
final readonly class Skipper
{
    /**
     * @param array<SkipVoterInterface> $skipVoters
     */
    public function __construct(
        private RectifiedAnalyzer $rectifiedAnalyzer,
        private array $skipVoters,
        private PathSkipper $pathSkipper,
        private CommentSkipper $commentSkipper,
    ) {
        Assert::allIsInstanceOf($this->skipVoters, SkipVoterInterface::class);
    }

    public function shouldSkipElement(string | object $element): bool
    {
        return $this->shouldSkipElementAndFilePath($element, __FILE__);
    }

    public function shouldSkipFilePath(string $filePath): bool
    {
        return $this->pathSkipper->shouldSkip($filePath);
    }

    public function shouldSkipElementAndFilePath(string | object $element, string $filePath): bool
    {
        foreach ($this->skipVoters as $skipVoter) {
            if (! $skipVoter->match($element)) {
                continue;
            }

            if (! $skipVoter->shouldSkip($element, $filePath)) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function shouldSkipCurrentNode(
        string | object $element,
        string $filePath,
        string $rectorClass,
        Node $node
    ): bool {
        if ($this->shouldSkipElementAndFilePath($element, $filePath)) {
            return true;
        }

        if ($this->rectifiedAnalyzer->hasRectified($rectorClass, $node)) {
            return true;
        }
        return $this->commentSkipper->shouldSkip($rectorClass, $node);
    }
}
