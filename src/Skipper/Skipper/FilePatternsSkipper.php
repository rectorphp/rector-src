<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use Rector\Skipper\Matcher\FileInfoMatcher;

final readonly class FilePatternsSkipper implements FileNodeSkipperInterface
{
    public function __construct(
        private FileInfoMatcher $fileInfoMatcher,
        /** @var string[] */
        private array $patterns,
    ) {
    }

    public function shouldSkip(string $fileName, ?Node $node): bool
    {
        return $this->fileInfoMatcher->doesFileInfoMatchPatterns($fileName, $this->patterns);
    }
}
