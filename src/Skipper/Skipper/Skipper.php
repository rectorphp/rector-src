<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\ProcessAnalyzer\RectifiedAnalyzer;
use Rector\Skipper\SkipVoter\ClassSkipVoter;
use Rector\Skipper\ValueObject\SkipMatch;

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
        private UsedSkipCollector $usedSkipCollector,
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

    public function shouldSkipElementAndFilePath(string | object $element, string $filePath): bool
    {
        $skipMatch = $this->matchSkip($element, $filePath);
        if (! $skipMatch instanceof SkipMatch) {
            return false;
        }

        $this->markSkipUsed($skipMatch);
        return true;
    }

    /**
     * Match a class/path skip without marking it used. Callers that can only tell whether the skip
     * actually prevented a change later on must mark it used themselves via markSkipUsed().
     */
    public function matchSkip(string | object $element, string $filePath): ?SkipMatch
    {
        if (! $this->classSkipVoter->match($element)) {
            return null;
        }

        return $this->classSkipVoter->matchSkip($element, $filePath);
    }

    public function markSkipUsed(SkipMatch $skipMatch): void
    {
        $this->usedSkipCollector->markUsed($skipMatch->getSkippedClass(), $skipMatch->getMatchedPath());
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function shouldSkipCurrentNode(string $rectorClass, Node $node): bool
    {
        return $this->rectifiedAnalyzer->hasRectified($rectorClass, $node);
    }
}
