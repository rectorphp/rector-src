<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ProcessAnalyzer\RectifiedAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Skipper\Contract\SkipVoterInterface;
use Rector\Skipper\SkipVoter\ClassSkipVoter;
use Rector\Skipper\SkipVoter\PathSkipVoter;
use Webmozart\Assert\Assert;

/**
 * @api
 * @see \Rector\Tests\Skipper\Skipper\Skipper\SkipperTest
 */
final class Skipper
{
    /**
     * @var string
     */
    private const FILE_ELEMENT = 'file_elements';

    /**
     * @var SkipVoterInterface[]
     */
    private array $skipVoters = [];

    public function __construct(
        private readonly ClassSkipVoter $classSkipVoter,
        private readonly PathSkipVoter $pathSkipVoter,
        private readonly RectifiedAnalyzer $rectifiedAnalyzer
    ) {
        $this->skipVoters = [$classSkipVoter, $pathSkipVoter];
    }

    public function shouldSkipElement(string | object $element): bool
    {
        return $this->shouldSkipElementAndFilePath($element, __FILE__);
    }

    public function shouldSkipFilePath(string $filePath): bool
    {
        return $this->shouldSkipElementAndFilePath(self::FILE_ELEMENT, $filePath);
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
    public function shouldSkipCurrentNode(string | object $element, string $filePath, string $rectorClass, Node $node): bool
    {
        if ($this->shouldSkipElementAndFilePath($element, $filePath)) {
            return true;
        }

        return $this->rectifiedAnalyzer->hasRectified($rectorClass, $node);
    }
}
