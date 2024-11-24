<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Rector\Contract\Rector\RectorInterface;
use Rector\ProcessAnalyzer\RectifiedAnalyzer;
use Rector\Skipper\SkipVoter\ClassSkipVoter;

/**
 * @api
 * @see \Rector\Tests\Skipper\Skipper\SkipperTest
 */
final readonly class Skipper
{
    public function __construct(
        private RectifiedAnalyzer $rectifiedAnalyzer,
        private PathSkipper $pathSkipper,
        private ClassSkipVoter $classSkipVoter,
    ) {
    }

    public function shouldSkipElement(string | object $element): bool
    {
        return $this->shouldSkipElementAndFilePath($element, __FILE__);
    }

    public function shouldSkipFilePath(string $filePath): bool
    {
        return $this->pathSkipper->shouldSkip($filePath);
    }

    public function shouldSkipElementAndFilePath(string | object $element, string $filePath, ?Node $node = null): bool
    {
        if (! $this->classSkipVoter->match($element)) {
            return false;
        }

        return $this->classSkipVoter->shouldSkip($element, $filePath, $node);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function shouldSkipCurrentNode(
        string | object $element,
        string $filePath,
        string $rectorClass,
        Node $node
    ): bool|int {
        if ($this->shouldSkipElementAndFilePath($element, $filePath, $node)) {
            // Don't go any deeper with this rule if we already know we will skip:
            return NodeVisitor::DONT_TRAVERSE_CHILDREN;
        }

        return $this->rectifiedAnalyzer->hasRectified($rectorClass, $node);
    }
}
